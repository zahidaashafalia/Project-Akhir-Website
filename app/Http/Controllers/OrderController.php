<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['menu', 'restaurant'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu kosong!');
        }

        $user = Auth::user();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();
        $defaultAddress = $addresses->where('is_default', true)->first();

        $restaurant = $cartItems->first()->restaurant;
        $subtotal = $cartItems->sum('total_price');
        $deliveryFee = $restaurant->delivery_fee;
        $serviceFee = max(1000, round($subtotal * 0.02));
        $discount = session('cart_discount', 0);
        $appliedVoucher = session('applied_voucher');
        $total = $subtotal + $deliveryFee + $serviceFee - $discount;

        return view('checkout.index', compact(
            'cartItems', 'addresses', 'defaultAddress', 'restaurant',
            'subtotal', 'deliveryFee', 'serviceFee', 'discount',
            'total', 'appliedVoucher', 'user'
        ));
    }

    public function process(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cod,ewallet,transfer,card,balance',
            'delivery_notes' => 'nullable|string|max:200',
        ]);

        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with(['menu', 'restaurant'])->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        $address = $user->addresses()->findOrFail($request->address_id);
        $restaurant = $cartItems->first()->restaurant;
        $subtotal = $cartItems->sum('total_price');
        $deliveryFee = $restaurant->delivery_fee;
        $serviceFee = max(1000, round($subtotal * 0.02));
        $discount = session('cart_discount', 0);
        $total = $subtotal + $deliveryFee + $serviceFee - $discount;

        // Balance check
        if ($request->payment_method === 'balance' && $user->balance < $total) {
            return back()->with('error', 'Saldo tidak mencukupi. Saldo kamu: Rp ' . number_format($user->balance, 0, ',', '.'));
        }

        DB::beginTransaction();
        try {
            $voucher = null;
            if (session('applied_voucher')) {
                $voucher = Voucher::find(session('applied_voucher')['id']);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'restaurant_id' => $restaurant->id,
                'address_id' => $address->id,
                'voucher_id' => $voucher?->id,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'service_fee' => $serviceFee,
                'discount_amount' => $discount,
                'total_amount' => $total,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'cod' ? 'unpaid' : 'unpaid',
                'delivery_address' => $address->full_address . ', ' . $address->city,
                'delivery_latitude' => $address->latitude,
                'delivery_longitude' => $address->longitude,
                'delivery_notes' => $request->delivery_notes,
                'estimated_delivery_at' => now()->addMinutes($restaurant->estimated_delivery_time + 15),
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item->menu_id,
                    'menu_name' => $item->menu->name,
                    'menu_image' => $item->menu->image,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                    'selected_options' => $item->selected_options,
                    'notes' => $item->notes,
                ]);
            }

            // Record status history
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'notes' => 'Pesanan berhasil dibuat',
                'changed_by' => $user->id,
                'created_at' => now(),
            ]);

            // Handle payment
            if ($request->payment_method === 'balance') {
                $user->deductBalance($total);
                $order->update(['payment_status' => 'paid', 'paid_at' => now()]);
            }

            // Handle voucher usage
            if ($voucher) {
                $voucher->increment('usage_count');
                VoucherUsage::create([
                    'voucher_id' => $voucher->id,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'discount_amount' => $discount,
                    'created_at' => now(),
                ]);
            }

            // Add loyalty points (1 point per Rp 1000)
            $points = (int) ($total / 1000);
            $user->addPoints($points);

            // Clear cart
            Cart::where('user_id', $user->id)->delete();
            session()->forget(['applied_voucher', 'cart_discount']);

            // Update restaurant stats
            $restaurant->increment('total_orders');

            DB::commit();

            // Generate payment URL for online payment
            if (!in_array($request->payment_method, ['cod', 'balance'])) {
                // Here you'd integrate Midtrans or other payment gateway
                // $paymentUrl = $this->generatePaymentUrl($order);
                // $order->update(['payment_url' => $paymentUrl]);
            }

            return redirect()->route('checkout.success', $order)
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);
        $order->load(['items', 'restaurant', 'address']);
        return view('checkout.success', compact('order'));
    }
}

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = Order::where('user_id', Auth::id())
            ->with(['restaurant', 'items'])
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(10);

        return view('orders.index', compact('orders', 'status'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);
        $order->load(['items.menu', 'restaurant', 'address', 'driver', 'statusHistories', 'review']);
        return view('orders.show', compact('order'));
    }

    public function track(Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);
        $order->load(['restaurant', 'driver', 'statusHistories', 'address']);
        return view('orders.track', compact('order'));
    }

    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);
        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan');
        }

        $request->validate(['reason' => 'required|string|max:200']);

        $order->update(['status' => 'cancelled', 'cancellation_reason' => $request->reason]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'cancelled',
            'notes' => $request->reason,
            'changed_by' => Auth::id(),
            'created_at' => now(),
        ]);

        // Refund if already paid
        if ($order->payment_status === 'paid') {
            Auth::user()->addBalance($order->total_amount);
            $order->update(['payment_status' => 'refunded']);
        }

        return back()->with('success', 'Pesanan berhasil dibatalkan');
    }

    public function reorder(Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);

        Cart::where('user_id', Auth::id())->delete();
        session()->forget(['applied_voucher', 'cart_discount']);

        foreach ($order->items as $item) {
            if ($item->menu && $item->menu->is_available) {
                Cart::create([
                    'user_id' => Auth::id(),
                    'restaurant_id' => $order->restaurant_id,
                    'menu_id' => $item->menu_id,
                    'quantity' => $item->quantity,
                    'selected_options' => $item->selected_options ?? [],
                    'notes' => $item->notes,
                    'unit_price' => $item->menu->effective_price,
                ]);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Item dari pesanan sebelumnya ditambahkan ke keranjang!');
    }
}
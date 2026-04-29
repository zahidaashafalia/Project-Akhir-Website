<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Http\Request;
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

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'notes' => 'Pesanan berhasil dibuat',
                'changed_by' => $user->id,
                'created_at' => now(),
            ]);

            if ($request->payment_method === 'balance') {
                $user->deductBalance($total);
                $order->update(['payment_status' => 'paid', 'paid_at' => now()]);
            }

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

            $points = (int) ($total / 1000);
            $user->addPoints($points);

            Cart::where('user_id', $user->id)->delete();
            session()->forget(['applied_voucher', 'cart_discount']);

            $restaurant->increment('total_orders');

            DB::commit();

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

    public function paymentCallback(Request $request)
    {
        return response()->json(['status' => 'ok']);
    }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\Auth;

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
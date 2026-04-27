<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['menu.restaurant', 'restaurant', 'menu.optionGroups.options'])
            ->get();

        $restaurant = $cartItems->first()?->restaurant;
        $subtotal = $cartItems->sum('total_price');
        $deliveryFee = $restaurant?->delivery_fee ?? 0;
        $serviceFee = max(1000, $subtotal * 0.02); // 2% or min Rp 1000
        $discount = session('cart_discount', 0);
        $total = $subtotal + $deliveryFee + $serviceFee - $discount;

        $appliedVoucher = session('applied_voucher');

        return view('cart.index', compact(
            'cartItems', 'restaurant', 'subtotal', 'deliveryFee',
            'serviceFee', 'discount', 'total', 'appliedVoucher'
        ));
    }

    public function add(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1|max:20',
            'notes' => 'nullable|string|max:200',
            'selected_options' => 'nullable|array',
        ]);

        $menu = Menu::with('restaurant')->findOrFail($request->menu_id);

        // Check if cart has items from different restaurant
        $existingCart = Cart::where('user_id', Auth::id())->first();
        if ($existingCart && $existingCart->restaurant_id !== $menu->restaurant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang kamu sudah berisi pesanan dari ' . $existingCart->restaurant->name . '. Kosongkan dulu ya!',
                'conflict' => true,
            ], 422);
        }

        // Check if menu already in cart (same options)
        $existingItem = Cart::where('user_id', Auth::id())
            ->where('menu_id', $menu->id)
            ->where('selected_options', json_encode($request->selected_options))
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $request->quantity);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'restaurant_id' => $menu->restaurant_id,
                'menu_id' => $menu->id,
                'quantity' => $request->quantity,
                'selected_options' => $request->selected_options ?? [],
                'notes' => $request->notes,
                'unit_price' => $menu->effective_price,
            ]);
        }

        $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => $menu->name . ' berhasil ditambahkan!',
            'cart_count' => $cartCount,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:0|max:20']);

        $item = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($request->quantity == 0) {
            $item->delete();
        } else {
            $item->update(['quantity' => $request->quantity]);
        }

        return response()->json(['success' => true, 'message' => 'Keranjang diperbarui']);
    }

    public function remove($id)
    {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return response()->json(['success' => true]);
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        session()->forget(['applied_voucher', 'cart_discount']);
        return response()->json(['success' => true]);
    }

    public function applyVoucher(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $voucher = Voucher::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->first();

        if (!$voucher || !$voucher->isValid()) {
            return response()->json(['success' => false, 'message' => 'Voucher tidak valid atau sudah kadaluarsa']);
        }

        $cartTotal = Cart::where('user_id', Auth::id())->get()->sum('total_price');
        $discount = $voucher->calculateDiscount($cartTotal);

        if ($discount == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order Rp ' . number_format($voucher->min_order, 0, ',', '.') . ' untuk voucher ini'
            ]);
        }

        session(['applied_voucher' => $voucher->toArray(), 'cart_discount' => $discount]);

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil digunakan! Hemat Rp ' . number_format($discount, 0, ',', '.'),
            'discount' => $discount,
        ]);
    }
}
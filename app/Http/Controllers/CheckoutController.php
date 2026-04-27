<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout.index');
    }

    public function process(Request $request)
    {
        return redirect()->route('checkout.success', ['order' => 1]);
    }

    public function success(Order $order)
    {
        return view('checkout.success', compact('order'));
    }

    public function paymentCallback(Request $request)
    {
        return response()->json(['status' => 'ok']);
    }
}


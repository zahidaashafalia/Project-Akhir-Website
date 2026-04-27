<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('partner.orders');
    }

    public function updateStatus(Request $request, $order)
    {
        return back()->with('success', 'Status diperbarui');
    }
}


<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        return view('admin.vouchers');
    }

    public function store(Request $request)
    {
        return back()->with('success', 'Voucher dibuat');
    }
}


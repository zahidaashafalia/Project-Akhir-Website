<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class OrderAdminController extends Controller
{
    public function index()
    {
        return view('admin.orders.index');
    }

    public function show($id)
    {
        return view('admin.orders.show');
    }

    public function edit($id)
    {
        return view('admin.orders.edit');
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        return redirect()->route('admin.orders.index');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.orders.index');
    }
}


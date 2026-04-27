<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class RestaurantAdminController extends Controller
{
    public function index()
    {
        return view('admin.restaurants.index');
    }

    public function create()
    {
        return view('admin.restaurants.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        return redirect()->route('admin.restaurants.index');
    }

    public function show($id)
    {
        return view('admin.restaurants.show');
    }

    public function edit($id)
    {
        return view('admin.restaurants.edit');
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        return redirect()->route('admin.restaurants.index');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.restaurants.index');
    }
}


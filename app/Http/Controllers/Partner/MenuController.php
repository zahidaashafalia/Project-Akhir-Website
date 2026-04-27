<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        return view('partner.menu');
    }

    public function store(Request $request)
    {
        return back()->with('success', 'Menu ditambahkan');
    }

    public function update(Request $request, $id)
    {
        return back()->with('success', 'Menu diperbarui');
    }

    public function destroy($id)
    {
        return back()->with('success', 'Menu dihapus');
    }
}


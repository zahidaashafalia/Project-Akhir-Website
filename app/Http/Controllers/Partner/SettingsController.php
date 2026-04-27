<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('partner.settings');
    }

    public function update(Request $request)
    {
        return back()->with('success', 'Pengaturan diperbarui');
    }
}


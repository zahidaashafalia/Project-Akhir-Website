<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        return view('account.index');
    }

    public function updateProfile(Request $request)
    {
        return back()->with('success', 'Profil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        return back()->with('success', 'Password diperbarui');
    }

    public function addresses()
    {
        return view('account.addresses');
    }

    public function storeAddress(Request $request)
    {
        return back()->with('success', 'Alamat ditambahkan');
    }

    public function updateAddress(Request $request, $id)
    {
        return back()->with('success', 'Alamat diperbarui');
    }

    public function destroyAddress($id)
    {
        return back()->with('success', 'Alamat dihapus');
    }

    public function vouchers()
    {
        return view('account.vouchers');
    }

    public function favorites()
    {
        return view('account.favorites');
    }

    public function toggleFavorite($restaurant)
    {
        $favorited = true;
        return response()->json(['favorited' => $favorited]);
    }

    public function wallet()
    {
        return view('account.wallet');
    }

    public function points()
    {
        return view('account.points');
    }

    public function notifications()
    {
        return view('account.notifications');
    }
}


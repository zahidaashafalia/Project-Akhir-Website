<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        return back()->with('success', 'Ulasan berhasil dikirim');
    }
}


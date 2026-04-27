<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;

class AnalyticsController extends Controller
{
    public function index()
    {
        return view('partner.analytics');
    }
}


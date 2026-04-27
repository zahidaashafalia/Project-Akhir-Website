<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function show($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->with(['categories', 'menus', 'reviews'])
            ->firstOrFail();

        return view('restaurant.show', compact('restaurant'));
    }
}


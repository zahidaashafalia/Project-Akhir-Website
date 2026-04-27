<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function show($slug, $menuId)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $menu = Menu::where('id', $menuId)
            ->where('restaurant_id', $restaurant->id)
            ->firstOrFail();

        return view('menu.show', compact('restaurant', 'menu'));
    }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Voucher;
use App\Models\Menu;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $featuredRestaurants = Restaurant::where('is_featured', true)
            ->where('is_active', true)
            ->where('status', 'active')
            ->with(['categories', 'reviews'])
            ->latest()
            ->take(8)
            ->get();

        $popularRestaurants = Restaurant::where('is_active', true)
            ->where('status', 'active')
            ->orderBy('total_orders', 'desc')
            ->orderBy('rating', 'desc')
            ->with(['categories'])
            ->take(12)
            ->get();

        $newRestaurants = Restaurant::where('is_active', true)
            ->where('status', 'active')
            ->latest()
            ->take(6)
            ->get();

        $freeDeliveryRestaurants = Restaurant::where('delivery_fee', 0)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(6)
            ->get();

        $flashSaleMenus = Menu::where('is_available', true)
            ->whereNotNull('discount_price')
            ->with('restaurant')
            ->inRandomOrder()
            ->take(8)
            ->get();

        $activeVouchers = Voucher::where('is_active', true)
            ->whereNull('restaurant_id')
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>', now());
            })
            ->take(4)
            ->get();

        return view('home.index', compact(
            'categories', 'featuredRestaurants', 'popularRestaurants',
            'newRestaurants', 'freeDeliveryRestaurants', 'flashSaleMenus',
            'activeVouchers'
        ));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $categorySlug = $request->get('category');
        $sortBy = $request->get('sort', 'popular');
        $minRating = $request->get('min_rating');
        $maxDeliveryFee = $request->get('max_delivery_fee');
        $tags = $request->get('tags', []);

        $restaurantsQuery = Restaurant::where('is_active', true)
            ->where('status', 'active')
            ->with(['categories']);

        if ($query) {
            $restaurantsQuery->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhereHas('menus', function ($mq) use ($query) {
                      $mq->where('name', 'like', "%{$query}%");
                  });
            });
        }

        if ($categorySlug) {
            $restaurantsQuery->whereHas('categories', fn($q) => $q->where('slug', $categorySlug));
        }

        if ($minRating) {
            $restaurantsQuery->where('rating', '>=', $minRating);
        }

        if ($maxDeliveryFee !== null) {
            $restaurantsQuery->where('delivery_fee', '<=', $maxDeliveryFee);
        }

        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $restaurantsQuery->whereJsonContains('tags', $tag);
            }
        }

        $restaurantsQuery = match ($sortBy) {
            'rating' => $restaurantsQuery->orderBy('rating', 'desc'),
            'delivery_time' => $restaurantsQuery->orderBy('estimated_delivery_time'),
            'delivery_fee' => $restaurantsQuery->orderBy('delivery_fee'),
            'new' => $restaurantsQuery->latest(),
            default => $restaurantsQuery->orderBy('total_orders', 'desc'),
        };

        $restaurants = $restaurantsQuery->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        // Also search menus
        $menus = collect();
        if ($query) {
            $menus = Menu::where('name', 'like', "%{$query}%")
                ->where('is_available', true)
                ->with('restaurant')
                ->take(6)
                ->get();
        }

        return view('home.search', compact('restaurants', 'categories', 'query', 'menus', 'sortBy'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $restaurants = Restaurant::whereHas('categories', fn($q) => $q->where('slug', $slug))
            ->where('is_active', true)
            ->with(['categories'])
            ->orderBy('rating', 'desc')
            ->paginate(12);

        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('home.category', compact('category', 'restaurants', 'categories'));
    }

    public function promo()
    {
        $vouchers = Voucher::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>', now());
            })
            ->with('restaurant')
            ->paginate(12);

        $flashSaleMenus = Menu::whereNotNull('discount_price')
            ->where('is_available', true)
            ->with('restaurant')
            ->orderByRaw('((price - discount_price) / price * 100) DESC')
            ->take(16)
            ->get();

        return view('home.promo', compact('vouchers', 'flashSaleMenus'));
    }
}
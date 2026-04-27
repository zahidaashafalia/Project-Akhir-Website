<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RestaurantAdminController;
use App\Http\Controllers\Admin\OrderAdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===== PUBLIC ROUTES =====
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category');
Route::get('/promo', [HomeController::class, 'promo'])->name('promo');

// Restaurant & Menu
Route::get('/restaurant/{slug}', [RestaurantController::class, 'show'])->name('restaurant.show');
Route::get('/restaurant/{slug}/menu/{menuId}', [MenuController::class, 'show'])->name('menu.show');

// Legacy product detail alias (prevents old cached views from breaking)
Route::get('/product/{menuId}', function ($menuId) {
    $menu = \App\Models\Menu::findOrFail($menuId);
    return redirect()->route('menu.show', [$menu->restaurant->slug, $menu->id]);
})->name('product.detail');

// ===== AUTH ROUTES =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ===== AUTHENTICATED ROUTES =====
Route::middleware('auth')->group(function () {

    // Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::put('/update/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::post('/apply-voucher', [CartController::class, 'applyVoucher'])->name('voucher');
    });

    // Checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/process', [CheckoutController::class, 'process'])->name('process');
        Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
        Route::post('/payment/callback', [CheckoutController::class, 'paymentCallback'])->name('payment.callback');
    });

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/track', [OrderController::class, 'track'])->name('track');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::post('/{order}/reorder', [OrderController::class, 'reorder'])->name('reorder');
    });

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Account
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [AccountController::class, 'updatePassword'])->name('password.update');
        Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
        Route::post('/addresses', [AccountController::class, 'storeAddress'])->name('addresses.store');
        Route::put('/addresses/{id}', [AccountController::class, 'updateAddress'])->name('addresses.update');
        Route::delete('/addresses/{id}', [AccountController::class, 'destroyAddress'])->name('addresses.destroy');
        Route::get('/vouchers', [AccountController::class, 'vouchers'])->name('vouchers');
        Route::get('/favorites', [AccountController::class, 'favorites'])->name('favorites');
        Route::post('/favorites/{restaurant}', [AccountController::class, 'toggleFavorite'])->name('favorites.toggle');
        Route::get('/wallet', [AccountController::class, 'wallet'])->name('wallet');
        Route::get('/points', [AccountController::class, 'points'])->name('points');
        Route::get('/notifications', [AccountController::class, 'notifications'])->name('notifications');
    });
});

// ===== RESTAURANT PARTNER ROUTES =====
Route::middleware(['auth', 'role:restaurant_owner'])->prefix('partner')->name('partner.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Partner\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/menu', [App\Http\Controllers\Partner\MenuController::class, 'index'])->name('menu.index');
    Route::post('/menu', [App\Http\Controllers\Partner\MenuController::class, 'store'])->name('menu.store');
    Route::put('/menu/{id}', [App\Http\Controllers\Partner\MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{id}', [App\Http\Controllers\Partner\MenuController::class, 'destroy'])->name('menu.destroy');
    Route::get('/orders', [App\Http\Controllers\Partner\OrderController::class, 'index'])->name('orders.index');
    Route::put('/orders/{order}/status', [App\Http\Controllers\Partner\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/analytics', [App\Http\Controllers\Partner\AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/settings', [App\Http\Controllers\Partner\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [App\Http\Controllers\Partner\SettingsController::class, 'update'])->name('settings.update');
});

// ===== ADMIN ROUTES =====
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/restaurants', RestaurantAdminController::class);
    Route::resource('/orders', OrderAdminController::class);
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/vouchers', [App\Http\Controllers\Admin\VoucherController::class, 'index'])->name('vouchers.index');
    Route::post('/vouchers', [App\Http\Controllers\Admin\VoucherController::class, 'store'])->name('vouchers.store');
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports');
});
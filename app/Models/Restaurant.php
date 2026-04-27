<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id', 'name', 'slug', 'description', 'logo', 'banner',
        'address', 'city', 'province', 'latitude', 'longitude',
        'phone', 'email', 'opening_hours', 'rating', 'total_reviews',
        'total_orders', 'min_order', 'delivery_fee', 'estimated_delivery_time',
        'is_open', 'is_active', 'is_featured', 'is_verified', 'tags', 'status',
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'tags' => 'array',
        'is_open' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'rating' => 'decimal:2',
        'min_order' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_restaurant');
    }

    public function menuGroups()
    {
        return $this->hasMany(MenuGroup::class)->orderBy('sort_order');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/restaurant-default.png');
    }

    public function getBannerUrlAttribute(): string
    {
        return $this->banner ? asset('storage/' . $this->banner) : asset('images/banner-default.jpg');
    }

    public function isCurrentlyOpen(): bool
    {
        $now = now();
        $dayKey = strtolower($now->format('D')); // mon, tue, etc.
        $hours = $this->opening_hours[$dayKey] ?? null;
        if (!$hours || !$this->is_open) return false;
        $open = \Carbon\Carbon::createFromTimeString($hours['open']);
        $close = \Carbon\Carbon::createFromTimeString($hours['close']);
        return $now->between($open, $close);
    }

    public function getDeliveryFeeFormattedAttribute(): string
    {
        return $this->delivery_fee == 0 ? 'Gratis' : 'Rp ' . number_format($this->delivery_fee, 0, ',', '.');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($restaurant) {
            if (!$restaurant->slug) {
                $restaurant->slug = \Str::slug($restaurant->name) . '-' . substr(uniqid(), -4);
            }
        });
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'restaurant_id', 'menu_group_id', 'name', 'slug', 'description',
        'image', 'price', 'discount_price', 'is_available', 'is_best_seller',
        'is_new', 'is_spicy', 'is_halal', 'is_vegetarian', 'calories',
        'rating', 'total_ordered', 'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_new' => 'boolean',
        'is_spicy' => 'boolean',
        'is_halal' => 'boolean',
        'is_vegetarian' => 'boolean',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function menuGroup()
    {
        return $this->belongsTo(MenuGroup::class);
    }

    public function optionGroups()
    {
        return $this->hasMany(MenuOptionGroup::class);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/food-default.png');
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->discount_price ?? $this->price;
    }

    public function hasDiscount(): bool
    {
        return !is_null($this->discount_price) && $this->discount_price < $this->price;
    }

    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->hasDiscount()) return 0;
        return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    public function getPriceFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getEffectivePriceFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->effective_price, 0, ',', '.');
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'restaurant_id', 'menu_group_id', 'name', 'description',
        'price', 'discount_price', 'image', 'is_available',
        'is_featured', 'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function menuGroup()
    {
        return $this->belongsTo(MenuGroup::class);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/menu-default.png');
    }

    public function hasDiscount(): bool
    {
        return $this->discount_price !== null && $this->discount_price > 0 && $this->discount_price < $this->price;
    }

    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->hasDiscount() || $this->price == 0) return 0;
        return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->hasDiscount() ? (float) $this->discount_price : (float) $this->price;
    }

    public function getEffectivePriceFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->effective_price, 0, ',', '.');
    }

    public function getPriceFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}


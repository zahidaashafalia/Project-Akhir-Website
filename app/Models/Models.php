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

class MenuGroup extends Model
{
    use HasFactory;

    protected $fillable = ['restaurant_id', 'name', 'description', 'sort_order', 'is_active'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class)->where('is_available', true)->orderBy('sort_order');
    }
}

class MenuOptionGroup extends Model
{
    use HasFactory;

    protected $fillable = ['menu_id', 'name', 'is_required', 'is_multiple', 'min_select', 'max_select'];

    protected $casts = ['is_required' => 'boolean', 'is_multiple' => 'boolean'];

    public function options()
    {
        return $this->hasMany(MenuOption::class);
    }
}

class MenuOption extends Model
{
    use HasFactory;

    protected $fillable = ['menu_option_group_id', 'name', 'additional_price', 'is_default', 'is_available'];

    protected $casts = ['additional_price' => 'decimal:2', 'is_default' => 'boolean', 'is_available' => 'boolean'];
}

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'image', 'color', 'sort_order', 'is_active'];

    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'category_restaurant');
    }
}

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'restaurant_id', 'menu_id', 'quantity', 'selected_options', 'notes', 'unit_price'];

    protected $casts = ['selected_options' => 'array', 'unit_price' => 'decimal:2'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function getTotalPriceAttribute(): float
    {
        $optionTotal = collect($this->selected_options ?? [])->sum('additional_price');
        return ($this->unit_price + $optionTotal) * $this->quantity;
    }
}

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'restaurant_id', 'driver_id', 'address_id',
        'voucher_id', 'subtotal', 'delivery_fee', 'service_fee', 'discount_amount',
        'total_amount', 'status', 'payment_method', 'payment_status', 'payment_token',
        'payment_url', 'paid_at', 'delivery_address', 'delivery_latitude',
        'delivery_longitude', 'delivery_notes', 'estimated_delivery_at',
        'delivered_at', 'cancellation_reason',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'estimated_delivery_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    const STATUSES = [
        'pending' => ['label' => 'Menunggu Konfirmasi', 'color' => 'yellow', 'icon' => '⏳'],
        'confirmed' => ['label' => 'Dikonfirmasi', 'color' => 'blue', 'icon' => '✅'],
        'preparing' => ['label' => 'Sedang Dimasak', 'color' => 'orange', 'icon' => '👨‍🍳'],
        'ready' => ['label' => 'Siap Diambil', 'color' => 'teal', 'icon' => '📦'],
        'picked_up' => ['label' => 'Diambil Kurir', 'color' => 'purple', 'icon' => '🛵'],
        'delivering' => ['label' => 'Sedang Diantar', 'color' => 'indigo', 'icon' => '🛵'],
        'delivered' => ['label' => 'Terkirim', 'color' => 'green', 'icon' => '✅'],
        'cancelled' => ['label' => 'Dibatalkan', 'color' => 'red', 'icon' => '❌'],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function getStatusInfoAttribute(): array
    {
        return self::STATUSES[$this->status] ?? ['label' => $this->status, 'color' => 'gray', 'icon' => '?'];
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_number = 'MK' . date('Ymd') . strtoupper(substr(uniqid(), -6));
        });
    }
}

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'menu_id', 'menu_name', 'menu_image', 'quantity', 'unit_price', 'total_price', 'selected_options', 'notes'];

    protected $casts = ['selected_options' => 'array', 'unit_price' => 'decimal:2', 'total_price' => 'decimal:2'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}

class OrderStatusHistory extends Model
{
    protected $fillable = ['order_id', 'status', 'notes', 'changed_by'];

    public $timestamps = false;

    protected $casts = ['created_at' => 'datetime'];
}

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id', 'user_id', 'restaurant_id', 'food_rating', 'delivery_rating',
        'packaging_rating', 'overall_rating', 'comment', 'images', 'is_anonymous',
        'replied_by', 'reply', 'replied_at',
    ];

    protected $casts = [
        'images' => 'array',
        'is_anonymous' => 'boolean',
        'replied_at' => 'datetime',
        'overall_rating' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id', 'code', 'title', 'description', 'type', 'value',
        'min_order', 'max_discount', 'usage_limit', 'usage_count',
        'per_user_limit', 'start_date', 'end_date', 'is_active', 'applicable_days',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'applicable_days' => 'array',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->start_date && now()->lt($this->start_date)) return false;
        if ($this->end_date && now()->gt($this->end_date)) return false;
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) return false;
        return true;
    }

    public function calculateDiscount(float $orderTotal): float
    {
        if ($orderTotal < $this->min_order) return 0;
        $discount = $this->type === 'percentage'
            ? $orderTotal * ($this->value / 100)
            : $this->value;
        if ($this->max_discount) $discount = min($discount, $this->max_discount);
        return $discount;
    }
}

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'label', 'recipient_name', 'phone', 'full_address', 'city', 'province', 'postal_code', 'latitude', 'longitude', 'notes', 'is_default'];

    protected $casts = ['is_default' => 'boolean', 'latitude' => 'decimal:8', 'longitude' => 'decimal:8'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'amount', 'balance_after', 'reference_type', 'reference_id', 'description'];

    protected $casts = ['amount' => 'decimal:2', 'balance_after' => 'decimal:2'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
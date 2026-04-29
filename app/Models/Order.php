<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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


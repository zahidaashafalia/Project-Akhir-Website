<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'restaurant_id', 'order_number',
        'status', 'payment_status', 'payment_method',
        'subtotal', 'delivery_fee', 'service_fee',
        'discount', 'total', 'notes',
        'delivery_address', 'delivery_latitude', 'delivery_longitude',
        'estimated_delivery_time', 'delivered_at',
        'voucher_id', 'voucher_discount',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'voucher_discount' => 'decimal:2',
        'delivered_at' => 'datetime',
        'estimated_delivery_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}


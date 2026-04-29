<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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


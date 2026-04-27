<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id', 'code', 'title', 'description',
        'type', 'value', 'min_order', 'max_discount',
        'max_usage', 'usage_count', 'start_date', 'end_date',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'usage_count' => 'integer',
        'max_usage' => 'integer',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}


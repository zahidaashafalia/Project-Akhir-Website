<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

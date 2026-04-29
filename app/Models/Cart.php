<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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


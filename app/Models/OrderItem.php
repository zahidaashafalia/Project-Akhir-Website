<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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


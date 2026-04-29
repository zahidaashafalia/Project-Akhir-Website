<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuOption extends Model
{
    use HasFactory;

    protected $fillable = ['menu_option_group_id', 'name', 'additional_price', 'is_default', 'is_available'];

    protected $casts = ['additional_price' => 'decimal:2', 'is_default' => 'boolean', 'is_available' => 'boolean'];
}


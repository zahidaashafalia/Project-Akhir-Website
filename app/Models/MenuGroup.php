<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id', 'name', 'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class)->orderBy('sort_order');
    }
}


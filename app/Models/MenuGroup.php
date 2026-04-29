<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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


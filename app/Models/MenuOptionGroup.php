<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuOptionGroup extends Model
{
    use HasFactory;

    protected $fillable = ['menu_id', 'name', 'is_required', 'is_multiple', 'min_select', 'max_select'];

    protected $casts = ['is_required' => 'boolean', 'is_multiple' => 'boolean'];

    public function options()
    {
        return $this->hasMany(MenuOption::class);
    }
}


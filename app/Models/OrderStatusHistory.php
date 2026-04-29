<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    protected $fillable = ['order_id', 'status', 'notes', 'changed_by'];

    public $timestamps = false;

    protected $casts = ['created_at' => 'datetime'];
}


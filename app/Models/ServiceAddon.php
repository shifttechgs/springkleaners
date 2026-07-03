<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAddon extends Model
{
    protected $fillable = ['key', 'label', 'price', 'description', 'sort_order'];

    protected $casts = [
        'price' => 'decimal:2',
        'sort_order' => 'integer',
    ];
}

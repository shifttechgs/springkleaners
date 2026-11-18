<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAddon extends Model
{
    protected $fillable = ['key', 'label', 'price', 'unit_label', 'description', 'sort_order'];

    protected $casts = [
        'price' => 'decimal:2',
        'sort_order' => 'integer',
    ];
}

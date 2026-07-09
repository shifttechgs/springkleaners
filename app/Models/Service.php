<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'slug', 'name', 'tagline', 'icon', 'base_price', 'included_bedrooms',
        'included_bathrooms', 'bedroom_price', 'bathroom_price', 'extra_room_price',
        'service_fee', 'avg_hours', 'unit_label', 'sort_order', 'bookable',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'bedroom_price' => 'decimal:2',
        'bathroom_price' => 'decimal:2',
        'extra_room_price' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'included_bedrooms' => 'integer',
        'included_bathrooms' => 'integer',
        'avg_hours' => 'integer',
        'sort_order' => 'integer',
        'bookable' => 'boolean',
    ];
}

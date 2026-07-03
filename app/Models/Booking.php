<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public const DAILY_CAPACITY = 2;

    protected $fillable = [
        'service', 'date', 'time', 'name', 'phone', 'address', 'suburb',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}

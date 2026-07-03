<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    public const DAILY_CAPACITY = 2;

    protected $fillable = [
        'client_id', 'service', 'date', 'time', 'name', 'phone', 'address', 'suburb',
        'property_type', 'bedrooms', 'bathrooms', 'extra_rooms', 'last_cleaned',
        'floor_types', 'pets', 'notes', 'access_instructions', 'parking',
        'addons', 'booking_type', 'frequency', 'subtotal', 'total',
        'status', 'quoted_price', 'admin_notes', 'accepted_token',
        'quote_sent_at', 'accepted_at',
        'invoice_number', 'invoiced_at', 'payment_status', 'payment_method', 'paid_at',
        'deposit_amount', 'deposit_paid_at', 'confirmation_sent_at', 'thank_you_sent_at',
    ];

    protected $casts = [
        'date' => 'date',
        'floor_types' => 'array',
        'addons' => 'array',
        'pets' => 'boolean',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'quoted_price' => 'decimal:2',
        'status' => BookingStatus::class,
        'quote_sent_at' => 'datetime',
        'accepted_at' => 'datetime',
        'invoiced_at' => 'datetime',
        'payment_status' => PaymentStatus::class,
        'paid_at' => 'datetime',
        'deposit_amount' => 'decimal:2',
        'deposit_paid_at' => 'datetime',
        'confirmation_sent_at' => 'datetime',
        'thank_you_sent_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}

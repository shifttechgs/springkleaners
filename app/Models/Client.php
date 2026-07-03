<?php

namespace App\Models;

use App\Support\PhoneNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'name', 'phone_raw', 'phone_normalized', 'email', 'suburb', 'notes',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Find an existing client by normalized phone, or create a new one.
     *
     * @param  array{name: string, phone: string, email?: string|null, suburb?: string|null}  $attrs
     */
    public static function findOrCreateByPhone(array $attrs): self
    {
        $normalized = PhoneNumber::normalize($attrs['phone']);

        $client = self::firstWhere('phone_normalized', $normalized);

        if ($client) {
            return $client;
        }

        return self::create([
            'name' => $attrs['name'],
            'phone_raw' => $attrs['phone'],
            'phone_normalized' => $normalized,
            'email' => $attrs['email'] ?? null,
            'suburb' => $attrs['suburb'] ?? null,
        ]);
    }
}

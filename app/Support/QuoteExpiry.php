<?php

namespace App\Support;

use App\Enums\BookingStatus;
use App\Models\Booking;

class QuoteExpiry
{
    /**
     * Flip any Pending/Quoted booking whose hold window has passed to Expired,
     * freeing its calendar slot. Cheap to call opportunistically — only
     * touches unresolved bookings, which is normally a small set.
     */
    public static function sweep(): int
    {
        $cutoff = now()->subDays((int) config('cleaning_services.quote_hold_days', 5));

        $stale = Booking::whereIn('status', [BookingStatus::Pending, BookingStatus::Quoted])
            ->where(function ($query) use ($cutoff) {
                $query->where('quote_sent_at', '<', $cutoff)
                    ->orWhere(function ($query) use ($cutoff) {
                        $query->whereNull('quote_sent_at')->where('created_at', '<', $cutoff);
                    });
            })
            ->get();

        foreach ($stale as $booking) {
            $booking->update(['status' => BookingStatus::Expired]);
        }

        return $stale->count();
    }
}

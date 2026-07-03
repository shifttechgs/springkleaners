<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Pending = 'pending';
    case Quoted = 'quoted';
    case Accepted = 'accepted';
    case Declined = 'declined';
    case Completed = 'completed';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Quoted => 'Quoted',
            self::Accepted => 'Accepted',
            self::Declined => 'Declined',
            self::Completed => 'Completed',
            self::Expired => 'Expired',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'bg-gray-100 text-gray-700',
            self::Quoted => 'bg-amber-100 text-amber-700',
            self::Accepted => 'bg-emerald-100 text-emerald-700',
            self::Declined => 'bg-rose-100 text-rose-700',
            self::Completed => 'bg-[#081d3a]/10 text-[#081d3a]',
            self::Expired => 'bg-slate-200 text-slate-600',
        };
    }

    public function dotColor(): string
    {
        return match ($this) {
            self::Pending => 'bg-gray-400',
            self::Quoted => 'bg-amber-500',
            self::Accepted => 'bg-emerald-500',
            self::Declined => 'bg-rose-500',
            self::Completed => 'bg-[#081d3a]',
            self::Expired => 'bg-slate-400',
        };
    }

    /**
     * Statuses that still occupy a calendar slot and count toward daily capacity.
     */
    public static function occupyingSlot(): array
    {
        return [self::Pending, self::Quoted, self::Accepted, self::Completed];
    }
}

<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Unpaid = 'unpaid';
    case Paid = 'paid';

    public function label(): string
    {
        return match ($this) {
            self::Unpaid => 'Unpaid',
            self::Paid => 'Paid',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Unpaid => 'bg-amber-100 text-amber-700',
            self::Paid => 'bg-emerald-100 text-emerald-700',
        };
    }

    public function dotColor(): string
    {
        return match ($this) {
            self::Unpaid => 'bg-amber-500',
            self::Paid => 'bg-emerald-500',
        };
    }
}

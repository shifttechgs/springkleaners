<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Staff = 'staff';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Staff => 'Staff',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Admin => 'bg-[#081d3a]/10 text-[#081d3a]',
            self::Staff => 'bg-gray-100 text-gray-700',
        };
    }
}

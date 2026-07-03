<?php

namespace App\Enums;

enum ExpenseCategory: string
{
    case Stock = 'stock';
    case Sundries = 'sundries';
    case Fuel = 'fuel';
    case Salaries = 'salaries';
    case Vehicle = 'vehicle';
    case Marketing = 'marketing';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Stock => 'Stock & Supplies',
            self::Sundries => 'Sundries',
            self::Fuel => 'Fuel',
            self::Salaries => 'Salaries',
            self::Vehicle => 'Vehicle',
            self::Marketing => 'Marketing',
            self::Other => 'Other',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Stock => 'bg-blue-100 text-blue-700',
            self::Sundries => 'bg-teal-100 text-teal-700',
            self::Fuel => 'bg-amber-100 text-amber-700',
            self::Salaries => 'bg-purple-100 text-purple-700',
            self::Vehicle => 'bg-rose-100 text-rose-700',
            self::Marketing => 'bg-emerald-100 text-emerald-700',
            self::Other => 'bg-gray-100 text-gray-700',
        };
    }

    public function dotColor(): string
    {
        return match ($this) {
            self::Stock => 'bg-blue-500',
            self::Sundries => 'bg-teal-500',
            self::Fuel => 'bg-amber-500',
            self::Salaries => 'bg-purple-500',
            self::Vehicle => 'bg-rose-500',
            self::Marketing => 'bg-emerald-500',
            self::Other => 'bg-gray-400',
        };
    }
}

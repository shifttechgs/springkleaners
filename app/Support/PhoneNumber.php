<?php

namespace App\Support;

class PhoneNumber
{
    /**
     * Normalize a South African phone number to a consistent "27XXXXXXXXX" form
     * so differently-formatted numbers ("081 000 0000", "+27 81 000 0000") dedupe
     * to the same client record.
     */
    public static function normalize(string $raw): string
    {
        $digits = preg_replace('/\D+/', '', $raw) ?? '';

        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            $digits = '27'.substr($digits, 1);
        }

        return $digits;
    }
}

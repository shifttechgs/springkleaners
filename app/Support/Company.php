<?php

namespace App\Support;

class Company
{
    public static function name(): string
    {
        return Setting::get('company_name', 'SpringKleaners');
    }

    public static function addressLines(): array
    {
        $address = Setting::get('company_address', "1 Stepney Road, Unit H1\nHampton Place\nParklands\nCape Town");

        return array_values(array_filter(array_map('trim', explode("\n", $address))));
    }

    public static function hqAddress(): string
    {
        return Setting::get('company_hq_address', '1 Stepney Road, Parklands, 7441, Cape Town, South Africa');
    }

    public static function regNo(): string
    {
        return Setting::get('company_reg_no', '2021/363748/07');
    }

    public static function cell(): string
    {
        return Setting::get('company_cell', '+27 815 274 711');
    }

    public static function email(): string
    {
        return Setting::get('company_email', 'bookings@springkleaners.co.za');
    }

    public static function bankName(): string
    {
        return Setting::get('banking_bank_name', 'Nedbank');
    }

    public static function branchCode(): string
    {
        return Setting::get('banking_branch_code', '104509');
    }

    public static function accountNo(): string
    {
        return Setting::get('banking_account_no', '1195899688');
    }

    public static function referenceNote(): string
    {
        return Setting::get('banking_reference_note', 'Please use your NAME as reference.');
    }

    public static function reviewUrl(): string
    {
        return Setting::get('review_url', 'https://share.google/xbwUGIXI4vO1DeLGP');
    }
}

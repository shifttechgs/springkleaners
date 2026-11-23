<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * End-of-Tenancy's tagline said "Move-in/move-out", overlapping the separate Move-In Cleaning service.
 * Only touches the row if it still holds the original text, so an edit made in Admin > Services since is kept.
 * The service name and slug are unchanged.
 */
return new class extends Migration
{
    private const OLD = 'Move-in/move-out cleaning to landlord and letting-agent standard.';

    private const NEW = 'Move-out cleaning to landlord and letting-agent standard.';

    public function up(): void
    {
        DB::table('services')
            ->where('slug', 'end-of-tenancy')
            ->where('tagline', self::OLD)
            ->update(['tagline' => self::NEW, 'updated_at' => now()]);
    }

    public function down(): void
    {
        DB::table('services')
            ->where('slug', 'end-of-tenancy')
            ->where('tagline', self::NEW)
            ->update(['tagline' => self::OLD, 'updated_at' => now()]);
    }
};

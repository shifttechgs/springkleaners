<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Whether the client agreed, when booking, to before & after photos of the cleaned areas (for our records and to
 * show our work online, nothing identifying). Optional and off by default (POPIA: consent must be freely given),
 * and shown on the quote so both sides can see what was agreed.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->boolean('photo_consent')->default(false)->after('parking');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('photo_consent');
        });
    }
};

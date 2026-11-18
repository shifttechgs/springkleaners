<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Unpaid invoices can be corrected. invoice_revised_at marks the latest correction (printed as "Revised" on the
 * invoice); invoice_sent_at records the last time it was emailed, so admin can see when a revision hasn't gone
 * out yet.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('invoice_revised_at')->nullable()->after('invoiced_at');
            $table->timestamp('invoice_sent_at')->nullable()->after('invoice_revised_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['invoice_revised_at', 'invoice_sent_at']);
        });
    }
};

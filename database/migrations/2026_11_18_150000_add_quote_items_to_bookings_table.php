<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Quotes an admin has edited by hand. When set, these lines (description, note, qty, unit price) are the quote:
 * the PDF, the client's quote page and the invoice all print them, and quoted_price is their sum. When null,
 * the lines are worked out automatically from the service and add-on prices.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->json('quote_items')->nullable()->after('quoted_price');
            $table->timestamp('quote_edited_at')->nullable()->after('quote_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['quote_items', 'quote_edited_at']);
        });
    }
};

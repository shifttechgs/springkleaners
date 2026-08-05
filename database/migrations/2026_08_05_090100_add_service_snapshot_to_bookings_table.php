<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Frozen copy of the service scope + add-on wording at the moment
            // the quote was sent, so reprinting an old quote or invoice never
            // shows text the client did not agree to.
            $table->json('service_snapshot')->nullable()->after('addons');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('service_snapshot');
        });
    }
};

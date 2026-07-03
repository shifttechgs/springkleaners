<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->after('id')->constrained()->nullOnDelete();

            // Property + wizard details already collected client-side but previously dropped.
            $table->string('property_type')->nullable();
            $table->string('bedrooms')->nullable();
            $table->string('bathrooms')->nullable();
            $table->string('extra_rooms')->nullable();
            $table->string('last_cleaned')->nullable();
            $table->json('floor_types')->nullable();
            $table->boolean('pets')->default(false);
            $table->text('notes')->nullable();
            $table->text('access_instructions')->nullable();
            $table->string('parking')->nullable();
            $table->json('addons')->nullable();
            $table->string('booking_type')->default('once-off');
            $table->string('frequency')->nullable();
            $table->decimal('subtotal', 8, 2)->nullable();
            $table->decimal('total', 8, 2)->nullable();

            // Quote workflow.
            $table->string('status')->default('pending')->index();
            $table->decimal('quoted_price', 8, 2)->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('accepted_token')->nullable()->unique();
            $table->timestamp('quote_sent_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('client_id');

            $table->dropColumn([
                'property_type', 'bedrooms', 'bathrooms', 'extra_rooms',
                'last_cleaned', 'floor_types', 'pets', 'notes',
                'access_instructions', 'parking', 'addons', 'booking_type', 'frequency',
                'subtotal', 'total',
                'status', 'quoted_price', 'admin_notes', 'accepted_token',
                'quote_sent_at', 'accepted_at',
            ]);
        });
    }
};

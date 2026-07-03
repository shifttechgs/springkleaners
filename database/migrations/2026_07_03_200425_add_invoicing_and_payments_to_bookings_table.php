<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->unique()->after('accepted_token');
            $table->timestamp('invoiced_at')->nullable()->after('invoice_number');
            $table->string('payment_status')->default('unpaid')->after('invoiced_at');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('payment_method');
            $table->decimal('deposit_amount', 10, 2)->nullable()->after('paid_at');
            $table->timestamp('deposit_paid_at')->nullable()->after('deposit_amount');
            $table->timestamp('confirmation_sent_at')->nullable()->after('deposit_paid_at');
            $table->timestamp('thank_you_sent_at')->nullable()->after('confirmation_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_number', 'invoiced_at', 'payment_status', 'payment_method',
                'paid_at', 'deposit_amount', 'deposit_paid_at', 'confirmation_sent_at', 'thank_you_sent_at',
            ]);
        });
    }
};

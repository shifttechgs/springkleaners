<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('services')->insert([
            'slug' => 'recurring-house-cleaning',
            'name' => 'Recurring / Weekly House Cleaning',
            'tagline' => 'A lighter maintenance clean, on a weekly or bi-weekly schedule.',
            'icon' => 'recurring',
            'base_price' => 750,
            'included_bedrooms' => 2,
            'included_bathrooms' => 1,
            'bedroom_price' => 100,
            'bathroom_price' => 100,
            'extra_room_price' => 80,
            'service_fee' => 0,
            'avg_hours' => 2,
            'unit_label' => 'visit',
            'sort_order' => 5,
            'bookable' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('services')->where('slug', 'recurring-house-cleaning')->delete();
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('services')->insert([
            'slug' => 'airbnb-turnover-cleaning',
            'name' => 'Airbnb & Short-Let Turnover Cleaning',
            'tagline' => 'Fast, photo-ready turnover cleaning between guests.',
            'icon' => 'turnover',
            'base_price' => 900,
            'included_bedrooms' => 1,
            'included_bathrooms' => 1,
            'bedroom_price' => 100,
            'bathroom_price' => 0,
            'extra_room_price' => 0,
            'service_fee' => 0,
            'avg_hours' => 2,
            'unit_label' => 'turnover',
            'sort_order' => 9,
            'bookable' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('services')->where('slug', 'airbnb-turnover-cleaning')->delete();
    }
};

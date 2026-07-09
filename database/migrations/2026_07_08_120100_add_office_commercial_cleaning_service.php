<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('services')->insert([
            'slug' => 'office-commercial-cleaning',
            'name' => 'Office & Commercial Cleaning',
            'tagline' => 'Per-m² office and commercial cleaning, quoted to your floor plan.',
            'icon' => 'office',
            'base_price' => 850,
            'included_bedrooms' => 0,
            'included_bathrooms' => 0,
            'bedroom_price' => 0,
            'bathroom_price' => 0,
            'extra_room_price' => 0,
            'service_fee' => 0,
            'avg_hours' => 0,
            'unit_label' => 'visit',
            'sort_order' => 4,
            'bookable' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('services')->where('slug', 'office-commercial-cleaning')->delete();
    }
};

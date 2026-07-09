<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('services')->insert([
            'slug' => 'move-in-cleaning',
            'name' => 'Move-In Cleaning',
            'tagline' => 'A fresh-start clean before you unpack a single box.',
            'icon' => 'movein',
            'base_price' => 1200,
            'included_bedrooms' => 2,
            'included_bathrooms' => 1,
            'bedroom_price' => 120,
            'bathroom_price' => 100,
            'extra_room_price' => 90,
            'service_fee' => 30,
            'avg_hours' => 5,
            'unit_label' => 'property',
            'sort_order' => 20,
            'bookable' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('services')->where('slug', 'move-in-cleaning')->delete();
    }
};

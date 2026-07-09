<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('services')->insert([
            [
                'slug' => 'carpet-cleaning',
                'name' => 'Carpet Cleaning',
                'tagline' => 'Hot-water extraction carpet cleaning, priced per room.',
                'icon' => 'carpet',
                'base_price' => 950,
                'included_bedrooms' => 0,
                'included_bathrooms' => 0,
                'bedroom_price' => 0,
                'bathroom_price' => 0,
                'extra_room_price' => 0,
                'service_fee' => 0,
                'avg_hours' => 0,
                'unit_label' => 'visit',
                'sort_order' => 6,
                'bookable' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'upholstery-cleaning',
                'name' => 'Upholstery Cleaning',
                'tagline' => 'Fabric & leather upholstery cleaning, priced per seat.',
                'icon' => 'upholstery',
                'base_price' => 800,
                'included_bedrooms' => 0,
                'included_bathrooms' => 0,
                'bedroom_price' => 0,
                'bathroom_price' => 0,
                'extra_room_price' => 0,
                'service_fee' => 0,
                'avg_hours' => 0,
                'unit_label' => 'visit',
                'sort_order' => 7,
                'bookable' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'window-cleaning',
                'name' => 'Window Cleaning',
                'tagline' => 'Interior & exterior window cleaning, streak-free.',
                'icon' => 'window',
                'base_price' => 1400,
                'included_bedrooms' => 0,
                'included_bathrooms' => 0,
                'bedroom_price' => 0,
                'bathroom_price' => 0,
                'extra_room_price' => 0,
                'service_fee' => 0,
                'avg_hours' => 0,
                'unit_label' => 'property',
                'sort_order' => 8,
                'bookable' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('services')->whereIn('slug', ['carpet-cleaning', 'upholstery-cleaning', 'window-cleaning'])->delete();
    }
};

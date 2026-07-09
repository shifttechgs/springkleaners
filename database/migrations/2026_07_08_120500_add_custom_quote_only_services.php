<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const SLUGS = [
        ['spring-cleaning', 'Spring Cleaning', 'A seasonal, top-to-bottom reset for the whole home.', 'spring', 10],
        ['oven-deep-cleaning', 'Oven Deep Cleaning', 'Interior degreasing for ovens, racks and glass doors.', 'oven', 11],
        ['fridge-appliance-cleaning', 'Fridge & Appliance Cleaning', 'Interior fridge, freezer and appliance cleaning.', 'appliance', 12],
        ['mattress-cleaning', 'Mattress Cleaning', 'Deep extraction cleaning for allergens, stains & odour.', 'mattress', 13],
        ['blind-curtain-cleaning', 'Blind & Curtain Cleaning', 'On-site dusting and deep cleaning for blinds & curtains.', 'blinds', 14],
        ['retail-shop-cleaning', 'Retail & Shop Cleaning', 'Shop floor, storefront and fitting room cleaning.', 'retail', 15],
        ['medical-clinic-cleaning', 'Medical & Clinic Cleaning', 'Hygiene-focused cleaning for practices & clinics.', 'medical', 16],
        ['restaurant-kitchen-cleaning', 'Restaurant & Commercial Kitchen Cleaning', 'Front-of-house and kitchen surface cleaning.', 'restaurant', 17],
        ['school-educational-cleaning', 'School & Educational Facility Cleaning', 'Classroom, hallway and ablution cleaning for schools.', 'school', 18],
        ['pressure-washing', 'Pressure Washing / Exterior Cleaning', 'Driveways, patios and exterior surface cleaning.', 'pressure', 19],
    ];

    public function up(): void
    {
        $rows = collect(self::SLUGS)->map(fn ($row) => [
            'slug' => $row[0],
            'name' => $row[1],
            'tagline' => $row[2],
            'icon' => $row[3],
            'base_price' => 0,
            'included_bedrooms' => 0,
            'included_bathrooms' => 0,
            'bedroom_price' => 0,
            'bathroom_price' => 0,
            'extra_room_price' => 0,
            'service_fee' => 0,
            'avg_hours' => 0,
            'unit_label' => null,
            'sort_order' => $row[4],
            'bookable' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ])->all();

        DB::table('services')->insert($rows);
    }

    public function down(): void
    {
        DB::table('services')->whereIn('slug', array_column(self::SLUGS, 0))->delete();
    }
};

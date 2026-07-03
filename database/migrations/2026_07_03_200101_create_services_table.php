<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->string('icon')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->unsignedInteger('included_bedrooms')->default(0);
            $table->unsignedInteger('included_bathrooms')->default(0);
            $table->decimal('bedroom_price', 10, 2)->default(0);
            $table->decimal('bathroom_price', 10, 2)->default(0);
            $table->decimal('extra_room_price', 10, 2)->default(0);
            $table->decimal('service_fee', 10, 2)->default(0);
            $table->unsignedInteger('avg_hours')->default(0);
            $table->string('unit_label')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('services')->insert([
            [
                'slug' => 'deep-cleaning',
                'name' => 'Deep Cleaning',
                'tagline' => 'A thorough top-to-bottom clean of every surface, corner and room.',
                'icon' => 'deep',
                'base_price' => 1200,
                'included_bedrooms' => 2,
                'included_bathrooms' => 1,
                'bedroom_price' => 100,
                'bathroom_price' => 100,
                'extra_room_price' => 80,
                'service_fee' => 30,
                'avg_hours' => 4,
                'unit_label' => 'visit',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'end-of-tenancy',
                'name' => 'End-of-Tenancy Cleaning',
                'tagline' => 'Move-in/move-out cleaning to landlord and letting-agent standard.',
                'icon' => 'eot',
                'base_price' => 1200,
                'included_bedrooms' => 2,
                'included_bathrooms' => 1,
                'bedroom_price' => 120,
                'bathroom_price' => 100,
                'extra_room_price' => 90,
                'service_fee' => 30,
                'avg_hours' => 5,
                'unit_label' => 'property',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'post-construction',
                'name' => 'Post Construction Cleaning',
                'tagline' => 'We remove what the builders left behind, to a move-in ready standard.',
                'icon' => 'construction',
                'base_price' => 1800,
                'included_bedrooms' => 2,
                'included_bathrooms' => 1,
                'bedroom_price' => 150,
                'bathroom_price' => 120,
                'extra_room_price' => 100,
                'service_fee' => 50,
                'avg_hours' => 6,
                'unit_label' => 'project',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

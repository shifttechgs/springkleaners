<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_addons', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->decimal('price', 10, 2);
            $table->string('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('service_addons')->insert([
            ['key' => 'windows', 'label' => 'Interior windows', 'price' => 200, 'description' => 'All interior glass, frames and sills.', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'balcony', 'label' => 'Balcony / patio', 'price' => 150, 'description' => 'Sweep, mop and tidy outdoor areas.', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'walls', 'label' => 'Wall mark removal', 'price' => 150, 'description' => 'Spot-clean scuffs in main rooms.', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'garage', 'label' => 'Garage cleaning', 'price' => 200, 'description' => 'Sweep out dust, leaves and clutter.', 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('service_addons');
    }
};

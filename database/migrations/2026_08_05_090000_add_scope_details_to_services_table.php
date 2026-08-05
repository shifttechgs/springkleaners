<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->text('description')->nullable()->after('tagline');
            $table->json('inclusions')->nullable()->after('description');
        });

        // Seed the scope copy from the public service pages so existing
        // services carry their details straight away. Admin can edit both
        // fields from Services > Edit afterwards.
        foreach (config('service_pages', []) as $slug => $content) {
            DB::table('services')->where('slug', $slug)->update([
                'description' => $content['intro'] ?? null,
                'inclusions' => isset($content['included']) ? json_encode(array_values($content['included'])) : null,
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['description', 'inclusions']);
        });
    }
};

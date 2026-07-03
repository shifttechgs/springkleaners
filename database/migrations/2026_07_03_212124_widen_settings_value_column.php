<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE settings MODIFY value TEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE settings MODIFY value VARCHAR(255) NULL');
    }
};

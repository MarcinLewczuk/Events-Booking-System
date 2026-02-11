<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Set auto increment to 10000000 for items table
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 10000000');
    }

    public function down(): void
    {
        // Reset to 1 if rolling back
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1');
    }
};
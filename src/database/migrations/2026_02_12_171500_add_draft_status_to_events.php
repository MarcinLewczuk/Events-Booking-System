<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds 'draft' status to events table
     */
    public function up(): void
    {
        // For MySQL, we need to modify the enum
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE events MODIFY status ENUM('draft', 'active', 'cancelled', 'completed') DEFAULT 'draft'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE events MODIFY status ENUM('active', 'cancelled', 'completed') DEFAULT 'active'");
        }
    }
};

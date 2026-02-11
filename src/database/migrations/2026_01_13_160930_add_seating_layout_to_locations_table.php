<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->integer('seating_rows')->default(10)->after('max_attendance');
            $table->integer('seating_columns')->default(10)->after('seating_rows');
            $table->json('disabled_seats')->nullable()->after('seating_columns'); // Store unavailable seats like ["A1", "B5"]
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['seating_rows', 'seating_columns', 'disabled_seats']);
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Add the column first
            $table->unsignedBigInteger('location_id')->nullable()->after('band_id');

            // Add the foreign key safely if the table exists
            if (Schema::hasTable('locations')) {
                $table->foreign('location_id')
                    ->references('id')
                    ->on('locations')
                    ->nullOnDelete(); // sets to NULL if location deleted
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'location_id')) {
                $table->dropForeign(['location_id']);
                $table->dropColumn('location_id');
            }
        });
    }
};

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
            // Change year_of_creation to allow a wider range (e.g., 1-9999)
            // This allows for antiques and historical items from before 1900
            $table->unsignedSmallInteger('year_of_creation')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Revert to original integer type if needed
            $table->integer('year_of_creation')->nullable()->change();
        });
    }
};
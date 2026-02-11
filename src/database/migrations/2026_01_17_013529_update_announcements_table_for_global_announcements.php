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
            $table->year('year_of_creation')->nullable()->after('dimensions');
            $table->decimal('weight', 8, 2)->nullable()->after('year_of_creation')->comment('Weight in kilograms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['year_of_creation', 'weight']);
        });
    }
};
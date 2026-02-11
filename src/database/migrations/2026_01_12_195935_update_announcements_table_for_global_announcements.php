<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Make topic nullable to allow 'general' announcements
            $table->string('topic')->nullable()->change();
            
            // Topic can now be 'auction', 'catalogue', or 'general' (null)
            // If both auction_id and catalogue_id are null, it's a general announcement to all users
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Revert topic to non-nullable
            $table->enum('topic', ['auction', 'catalogue'])->nullable(false)->change();
        });
    }
};
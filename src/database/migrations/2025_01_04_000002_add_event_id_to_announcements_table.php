<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            if (!Schema::hasColumn('announcements', 'event_id')) {
                $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade')->after('catalogue_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            if (Schema::hasColumn('announcements', 'event_id')) {
                $table->dropForeignIdFor('Event');
                $table->dropColumn('event_id');
            }
        });
    }
};

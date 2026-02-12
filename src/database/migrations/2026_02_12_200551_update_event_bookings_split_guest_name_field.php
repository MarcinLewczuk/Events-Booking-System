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
        Schema::table('event_bookings', function (Blueprint $table) {
            $table->string('guest_first_name')->nullable()->after('user_id');
            $table->string('guest_surname')->nullable()->after('guest_first_name');
            $table->dropColumn('guest_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_bookings', function (Blueprint $table) {
            $table->string('guest_name')->nullable()->after('user_id');
            $table->dropColumn(['guest_first_name', 'guest_surname']);
        });
    }
};

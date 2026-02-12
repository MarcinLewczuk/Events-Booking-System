<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_reference')->unique();
            $table->foreignId('event_booking_id')->constrained('event_bookings')->cascadeOnDelete();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->enum('type', ['adult', 'child', 'concession']);
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('status', ['valid', 'used', 'cancelled'])->default('valid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

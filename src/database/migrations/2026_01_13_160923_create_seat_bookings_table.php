<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seat_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained('auctions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('seat_number');
            $table->enum('status', ['reserved', 'confirmed', 'cancelled'])->default('reserved');
            $table->timestamp('reserved_at')->useCurrent();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            
            // A user can only book one seat per auction
            $table->unique(['auction_id', 'user_id']);
            // A seat can only be booked once per auction
            $table->unique(['auction_id', 'seat_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seat_bookings');
    }
};
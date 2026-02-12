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
        Schema::create('event_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference')->unique(); // e.g., "DLA-20260212-ABC123"
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Null for guest bookings
            
            // Guest info (for non-authenticated bookings)
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            
            // Ticket quantities
            $table->integer('adult_tickets')->default(0);
            $table->integer('child_tickets')->default(0);
            $table->integer('concession_tickets')->default(0);
            $table->integer('total_tickets'); // Sum of all ticket types
            
            // Pricing
            $table->decimal('total_amount', 10, 2)->default(0);
            
            // Additional info
            $table->text('accessibility_notes')->nullable();
            $table->boolean('newsletter_opt_in')->default(false);
            
            // Status tracking
            $table->enum('status', ['confirmed', 'cancelled'])->default('confirmed');
            $table->timestamp('cancelled_at')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->string('cancellation_reason')->nullable();
            
            // Email tracking
            $table->boolean('confirmation_email_sent')->default(false);
            $table->boolean('week_reminder_sent')->default(false);
            $table->boolean('day_reminder_sent')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index('booking_reference');
            $table->index(['event_id', 'status']);
            $table->index('guest_email');
            
            // Prevent duplicate bookings - a user/email can only book once per event
            // This is enforced at application level for flexibility
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_bookings');
    }
};

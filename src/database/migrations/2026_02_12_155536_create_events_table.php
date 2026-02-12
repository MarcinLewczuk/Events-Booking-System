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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('itinerary')->nullable();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->integer('duration_minutes'); // Calculated duration
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->integer('capacity');
            $table->boolean('is_paid')->default(false);
            $table->decimal('adult_price', 8, 2)->default(0);
            $table->decimal('child_price', 8, 2)->default(0);
            $table->decimal('concession_price', 8, 2)->default(0);
            $table->string('primary_image')->nullable();
            $table->json('gallery_images')->nullable(); // Array of image paths
            $table->enum('status', ['active', 'cancelled', 'completed'])->default('active');
            $table->timestamps();
            
            // Indexes
            $table->index('start_datetime');
            $table->index('status');
            $table->index(['location_id', 'start_datetime']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

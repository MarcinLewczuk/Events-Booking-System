<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('short_description')->nullable();
            $table->text('detailed_description')->nullable();

            // Add dimensions column
            $table->text('dimensions')->nullable()->comment('Physical dimensions of the item');

            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('estimated_price', 10, 2)->nullable();
            $table->decimal('reserve_price', 10, 2)->nullable();
            $table->foreignId('band_id')->nullable()->constrained()->nullOnDelete();

            // Intake and status
            $table->enum('intake_tier', ['featured', 'general'])->default('general');
            $table->enum('status', [
                'intake',
                'photos',
                'description',
                'catalogue_ready',
                'awaiting_approval',
                'published'
            ])->default('intake');

            // Primary image
            $table->unsignedBigInteger('primary_image_id')->nullable();

            // Created and approved
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            // Optional fields
            $table->text('override_reason')->nullable();
            $table->boolean('priority_flag')->default(false);
            $table->decimal('withdrawal_fee', 10, 2)->nullable();
            $table->timestamp('current_stage_entered_at')->nullable();

            $table->timestamps();
        });

        // Add location_id safely in a separate step
        if (Schema::hasTable('locations')) {
            Schema::table('items', function (Blueprint $table) {
                $table->foreignId('location_id')
                    ->nullable()
                    ->after('band_id')
                    ->constrained('locations')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'location_id')) {
                $table->dropForeign(['location_id']);
                $table->dropColumn('location_id');
            }
        });

        Schema::dropIfExists('items');
    }
};

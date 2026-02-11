<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('message');

            $table->enum('topic', ['auction', 'catalogue']);

            // Exactly one of these should be set (enforced in app logic)
            $table->foreignId('auction_id')
                ->nullable()
                ->constrained('auctions')
                ->cascadeOnDelete();

            $table->foreignId('catalogue_id')
                ->nullable()
                ->constrained('catalogues')
                ->cascadeOnDelete();

            $table->foreignId('created_by')
                ->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();

            // links to a catalogue
            $table->foreignId('catalogue_id')->constrained('catalogues')->onDelete('cascade');

            $table->string('title');
            $table->date('auction_date')->nullable();
            $table->time('start_time')->nullable();

            $table->enum('status', ['scheduled', 'open', 'closed', 'settled'])->default('scheduled');

            // admin who created auction
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

            // optional block column (not used)
            $table->string('auction_block')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};

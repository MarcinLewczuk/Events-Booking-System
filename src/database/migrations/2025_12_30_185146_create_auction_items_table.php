<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('auction_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('auction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained();

            $table->decimal('price', 10, 2)->nullable();

            $table->enum('sale_status', ['unsold', 'sold', 'withdrawn'])->default('unsold');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auction_items');
    }
};
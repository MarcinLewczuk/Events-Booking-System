<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();

            $table->foreignId('auction_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('auction_customer_id')->constrained()->cascadeOnDelete();

            $table->decimal('bid_amount', 10, 2);
            $table->enum('bid_type', ['online', 'telephone', 'commission']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};

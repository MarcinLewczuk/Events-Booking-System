<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('auction_item_id')->constrained()->cascadeOnDelete();

            $table->foreignId('buyer_customer_id')->constrained('users');
            $table->foreignId('seller_customer_id')->constrained('users');

            $table->decimal('hammer_price', 10, 2);
            $table->decimal('commission', 10, 2);
            $table->decimal('total_due', 10, 2);

            $table->enum('payment_status', ['pending', 'paid', 'overdue'])->default('pending');

            $table->date('settlement_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settlements');
    }
};

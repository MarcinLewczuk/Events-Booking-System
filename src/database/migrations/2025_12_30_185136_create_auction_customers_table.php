<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('auction_customers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('auction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users');

            $table->integer('bidder_number');
            $table->timestamp('registered_at')->useCurrent();

            $table->unique(['auction_id', 'bidder_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auction_customers');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('catalogue_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('catalogue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained();

            $table->integer('lot_number')->nullable();
            $table->integer('display_order');

            $table->string('title_override')->nullable();
            $table->text('description_override')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogue_items');
    }
};
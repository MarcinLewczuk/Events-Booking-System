<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('catalogues', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // Add category_id as a nullable foreign key
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');

            $table->enum('status', ['draft', 'awaiting_approval', 'published'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogues');
    }
};

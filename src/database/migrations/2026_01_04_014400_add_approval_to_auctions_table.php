<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            // Add approval status - auction must be approved before it can be scheduled
            $table->enum('approval_status', ['draft', 'awaiting_approval', 'approved', 'rejected'])
                ->default('draft')
                ->after('status');
            
            // Track who approved it
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('approval_status');
            
            // Track when approval status changed
            $table->timestamp('approval_status_changed_at')->nullable()->after('approved_by');
            
            // Optional rejection reason
            $table->text('rejection_reason')->nullable()->after('approval_status_changed_at');
        });
    }

    public function down(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['approval_status', 'approved_by', 'approval_status_changed_at', 'rejection_reason']);
        });
    }
};
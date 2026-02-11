<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Remove the default 'name' column if exists
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }

            // Add new name-related columns
            if (!Schema::hasColumn('users', 'title')) {
                $table->string('title', 50)->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name', 100)->nullable()->after('title');
            }
            if (!Schema::hasColumn('users', 'surname')) {
                $table->string('surname', 100)->nullable()->after('first_name');
            }

            // Contact fields
            if (!Schema::hasColumn('users', 'contact_address')) {
                $table->text('contact_address')->nullable()->after('surname');
            }
            if (!Schema::hasColumn('users', 'contact_telephone_number')) {
                $table->string('contact_telephone_number', 50)->nullable()->after('contact_address');
            }
            if (!Schema::hasColumn('users', 'contact_email')) {
                $table->string('contact_email', 150)->nullable()->after('contact_telephone_number');
            }

            // Buyer approved status
            if (!Schema::hasColumn('users', 'buyer_approved_status')) {
                $table->boolean('buyer_approved_status')->default(false)->after('contact_email');
            }

            // Bank details
            if (!Schema::hasColumn('users', 'bank_account_no')) {
                $table->string('bank_account_no', 50)->nullable()->after('buyer_approved_status');
            }
            if (!Schema::hasColumn('users', 'bank_sort_code')) {
                $table->string('bank_sort_code', 20)->nullable()->after('bank_account_no');
            }

            // Existing fields from your previous migration
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 50)->nullable()->after('contact_email');
            }
            if (!Schema::hasColumn('users', 'customer_consent_profile')) {
                $table->boolean('customer_consent_profile')->default(false)->after('phone');
            }
            if (!Schema::hasColumn('users', 'auction_display_setting')) {
                $table->enum('auction_display_setting', ['anonymous', 'name', 'business'])->nullable()->after('customer_consent_profile');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'staff', 'approver', 'customer'])->default('customer')->after('auction_display_setting');
            } else {
                $table->enum('role', ['admin', 'staff', 'approver', 'customer'])->default('customer')->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'first_name',
                'surname',
                'contact_address',
                'contact_telephone_number',
                'contact_email',
                'buyer_approved_status',
                'bank_account_no',
                'bank_sort_code'
            ]);

            // Optionally restore the default Laravel 'name' column
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->after('id');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update any locations with Canary Wharf to Delapré Abbey
        DB::table('locations')
            ->where('name', 'like', '%Canary Wharf%')
            ->orWhere('address', 'like', '%Canary Wharf%')
            ->update([
                'name' => 'Delapré Abbey',
                'address' => 'London Road, Northampton',
                'description' => 'Historic Delapré Abbey, a stunning venue in Northampton with rich heritage and beautiful grounds.',
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse this migration as we don't know the original values
    }
};

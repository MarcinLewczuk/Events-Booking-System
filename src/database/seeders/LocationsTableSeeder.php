<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('locations')->insert([
            // No locations seeded - site is for Delapré Abbey only
            // Locations should be created through the admin panel
        ]);
    }
}


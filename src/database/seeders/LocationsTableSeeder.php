<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('locations')->insert([
            [
                'name' => 'Delapré Abbey',
                'address' => 'London Rd, Northampton NN4 8AW',
                'description' => 'Historic Grade II* listed building with 900 years of history',
                'max_attendance' => 500,
                'latitude' => 52.22615116781254,
                'longitude' => -0.8897870554191317,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}


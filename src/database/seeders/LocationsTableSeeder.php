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
                'name' => 'London',
                'max_attendance' => 800,
                'address' => '1 Canada Square, Canary Wharf, London E14 5AB, United Kingdom',
                'description' => 'Located in the heart of Canary Wharf, this modern venue offers excellent transport connections and a professional setting ideal for large conferences and events. Surrounded by hotels, restaurants, and business centers, it provides a convenient and vibrant environment for attendees while maintaining high standards of comfort and accessibility.',
                'seating_rows' => 20,
                'seating_columns' => 40,
                'image_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Paris',
                'max_attendance' => 700,
                'address' => '2 Place de la Porte Maillot, 75017 Paris, France',
                'description' => 'Situated near the Palais des Congrès, this Paris venue combines elegance with functionality. Its central location allows easy access to public transport and nearby accommodations, making it an excellent choice for international events, exhibitions, and corporate gatherings in one of Europe’s most iconic cities.',
                'seating_rows' => 20,
                'seating_columns' => 35,
                'image_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'New York',
                'max_attendance' => 700,
                'address' => '429 11th Avenue, New York, NY 10001, United States',
                'description' => 'Located in Manhattan’s Hudson Yards district, this spacious venue is designed to host high-profile events and large audiences. With state-of-the-art facilities, excellent transport links, and proximity to hotels and dining options, it provides a dynamic and professional setting suitable for conferences, product launches, and international gatherings.',
                'seating_rows' => 20,
                'seating_columns' => 35,
                'image_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

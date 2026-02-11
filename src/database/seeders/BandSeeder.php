<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Band;

class BandSeeder extends Seeder
{
    public function run(): void
    {
        $bands = [
            [
                'name' => 'Budget',
                'description' => 'Low value items suitable for entry-level collectors',
                'min_price' => 0.00,
                'max_price' => 100.00,
                'requires_approval' => false,
            ],
            [
                'name' => 'Standard',
                'description' => 'Mid-range items with moderate value',
                'min_price' => 100.01,
                'max_price' => 500.00,
                'requires_approval' => false,
            ],
            [
                'name' => 'Premium',
                'description' => 'High-quality items requiring expert evaluation',
                'min_price' => 500.01,
                'max_price' => 2000.00,
                'requires_approval' => true,
            ],
            [
                'name' => 'Luxury',
                'description' => 'Luxury items with significant value',
                'min_price' => 2000.01,
                'max_price' => 10000.00,
                'requires_approval' => true,
            ],
            [
                'name' => 'Elite',
                'description' => 'Elite items requiring detailed documentation',
                'min_price' => 10000.01,
                'max_price' => 50000.00,
                'requires_approval' => true,
            ],
            [
                'name' => 'Exceptional',
                'description' => 'Exceptional items of museum quality',
                'min_price' => 50000.01,
                'max_price' => null, // No upper limit
                'requires_approval' => true,
            ],
        ];

        foreach ($bands as $bandData) {
            Band::create($bandData);
        }
    }
}
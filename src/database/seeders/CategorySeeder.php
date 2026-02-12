<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Yoga', 'parent_id' => null],
            ['name' => 'Art gallery', 'parent_id' => null],
            ['name' => 'History', 'parent_id' => null],
            ['name' => 'Oktoberfest', 'parent_id' => null],
            ['name' => 'Summertime rave', 'parent_id' => null],
            ['name' => 'Big screen event', 'parent_id' => null],
            ['name' => 'Workshops', 'parent_id' => null],
            ['name' => 'Heritage tours', 'parent_id' => null],
            ['name' => 'Family activities', 'parent_id' => null],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
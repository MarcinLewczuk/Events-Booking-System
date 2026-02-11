<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Art', 'parent_id' => null],
            ['name' => 'Furniture', 'parent_id' => null],
            ['name' => 'Jewellery', 'parent_id' => null],
            ['name' => 'Ceramics', 'parent_id' => null],
            ['name' => 'Glassware', 'parent_id' => null],
            ['name' => 'Silver & Metalware', 'parent_id' => null],
            ['name' => 'Books & Manuscripts', 'parent_id' => null],
            ['name' => 'Collectibles', 'parent_id' => null],
            ['name' => 'Textiles', 'parent_id' => null],
            ['name' => 'Toys & Games', 'parent_id' => null],
            ['name' => 'Musical Instruments', 'parent_id' => null],
            ['name' => 'Clocks & Watches', 'parent_id' => null],
            ['name' => 'Lighting', 'parent_id' => null],
            ['name' => 'Garden & Architectural', 'parent_id' => null],
            ['name' => 'Wine & Spirits', 'parent_id' => null],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Add some subcategories
        $artCategory = Category::where('name', 'Art')->first();
        if ($artCategory) {
            Category::create(['name' => 'Paintings', 'parent_id' => $artCategory->id]);
            Category::create(['name' => 'Sculptures', 'parent_id' => $artCategory->id]);
            Category::create(['name' => 'Prints', 'parent_id' => $artCategory->id]);
        }

        $furnitureCategory = Category::where('name', 'Furniture')->first();
        if ($furnitureCategory) {
            Category::create(['name' => 'Tables', 'parent_id' => $furnitureCategory->id]);
            Category::create(['name' => 'Chairs', 'parent_id' => $furnitureCategory->id]);
            Category::create(['name' => 'Cabinets', 'parent_id' => $furnitureCategory->id]);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalogue;
use App\Models\Category;
use App\Models\User;

class CatalogueSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $staff = User::where('role', 'staff')->first();
        $categories = Category::whereNull('parent_id')->get();

        if (!$admin || !$staff || $categories->isEmpty()) {
            return;
        }

        $catalogues = [
            [
                'name' => 'Spring 2026 Fine Art Collection',
                'category_id' => $categories->where('name', 'Art')->first()?->id ?? $categories->first()->id,
                'status' => 'published',
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Antique Furniture Showcase',
                'category_id' => $categories->where('name', 'Furniture')->first()?->id ?? $categories->skip(1)->first()->id,
                'status' => 'published',
                'created_by' => $staff->id,
            ],
            [
                'name' => 'Vintage Jewellery Estate Sale',
                'category_id' => $categories->where('name', 'Jewellery')->first()?->id ?? $categories->skip(2)->first()->id,
                'status' => 'awaiting_approval',
                'created_by' => $staff->id,
            ],
            [
                'name' => 'Collectible Toys & Games',
                'category_id' => $categories->where('name', 'Collectibles')->first()?->id ?? $categories->skip(3)->first()->id,
                'status' => 'draft',
                'created_by' => $staff->id,
            ],
            [
                'name' => 'Contemporary Ceramics Exhibition',
                'category_id' => $categories->where('name', 'Ceramics')->first()?->id ?? $categories->skip(4)->first()->id,
                'status' => 'published',
                'created_by' => $admin->id,
            ],
        ];

        foreach ($catalogues as $catalogueData) {
            Catalogue::create($catalogueData);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            // Music Genres
            ['name' => 'Classical Music', 'description' => 'Classical music performances and concerts'],
            ['name' => 'Jazz', 'description' => 'Jazz music events and performances'],
            ['name' => 'Folk Music', 'description' => 'Traditional and folk music'],
            
            // Event Types
            ['name' => 'Concert', 'description' => 'Live music concerts'],
            ['name' => 'Workshop', 'description' => 'Educational workshops and classes'],
            ['name' => 'Guided Tour', 'description' => 'Guided tours of the Abbey'],
            
            // Heritage & History
            ['name' => 'Heritage', 'description' => 'Heritage and historical content'],
            ['name' => 'Medieval History', 'description' => 'Medieval period and history'],
            ['name' => 'Tudor Period', 'description' => 'Tudor era history and culture'],
            
            // Arts & Crafts
            ['name' => 'Arts & Crafts', 'description' => 'Arts and crafts activities'],
            ['name' => 'Painting', 'description' => 'Painting workshops and classes'],
            ['name' => 'Pottery', 'description' => 'Pottery and ceramics workshops'],
            
            // Family & Children
            ['name' => 'Family-Friendly', 'description' => 'Suitable for all the family'],
            ['name' => 'Children', 'description' => 'Events for children'],
            ['name' => 'Toddlers', 'description' => 'Activities for toddlers'],
            ['name' => 'Teenagers', 'description' => 'Teen-focused events'],
            
            // Nature & Gardens
            ['name' => 'Garden Tour', 'description' => 'Tours of the Abbey gardens'],
            ['name' => 'Nature Walk', 'description' => 'Nature walks and wildlife spotting'],
            ['name' => 'Wildlife', 'description' => 'Wildlife and natural history'],
            
            // Wellbeing & Lifestyle
            ['name' => 'Yoga', 'description' => 'Yoga classes and sessions'],
            ['name' => 'Meditation', 'description' => 'Meditation and mindfulness'],
            
            // Food & Drink
            ['name' => 'Food & Drink', 'description' => 'Food and beverage events'],
            ['name' => 'Cookery', 'description' => 'Cooking classes and demonstrations'],
            
            // Seasonal & Special Events
            ['name' => 'Christmas', 'description' => 'Christmas events and celebrations'],
            ['name' => 'Easter', 'description' => 'Easter events and activities'],
            
            // Activity Level
            ['name' => 'Outdoor', 'description' => 'Outdoor activities and events'],
            ['name' => 'Indoor', 'description' => 'Indoor events'],
            
            // Special Interest
            ['name' => 'Literature', 'description' => 'Literary events and book clubs'],
            ['name' => 'Poetry', 'description' => 'Poetry readings and workshops'],
            ['name' => 'Genealogy', 'description' => 'Family history and genealogy'],
            
            // Venue Spaces
            ['name' => 'Great Hall', 'description' => 'Events in the Great Hall'],
            ['name' => 'Cloisters', 'description' => 'Events in the historic cloisters'],
            
            // Special Features
            ['name' => 'Behind the Scenes', 'description' => 'Exclusive behind the scenes access'],
            ['name' => 'Limited Capacity', 'description' => 'Small group events'],
            ['name' => 'Evening Event', 'description' => 'Evening events'],
        ];

        foreach ($tags as $tagData) {
            Tag::create([
                'name' => $tagData['name'],
                'slug' => Str::slug($tagData['name']),
                'description' => $tagData['description'],
            ]);
        }
    }
}

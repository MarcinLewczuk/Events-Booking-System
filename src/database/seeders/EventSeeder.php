<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Location;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a location (assumes locations exist)
        $location = Location::first();
        
        if (!$location) {
            $location = Location::create([
                'name' => 'Delapré Abbey',
                'address' => 'London Road, Northampton, NN4 8AW',
                'description' => 'Historic 900-year-old Grade II* listed building',
                'max_attendance' => 100,
            ]);
        }

        // Free Event - Heritage Tour
        Event::create([
            'title' => 'Guided Heritage Tour',
            'description' => 'Join us for an exclusive guided tour of Delapré Abbey\'s 900-year history. Discover the stories of the nuns, the Tate family, and the building\'s transformation through the centuries. Our expert guides will take you through the beautifully restored rooms and share fascinating insights into this Grade II* listed building.',
            'itinerary' => "10:00 AM - Welcome and Introduction\n10:15 AM - Ground Floor Tour\n11:00 AM - First Floor Galleries\n11:30 AM - Gardens and Grounds\n12:00 PM - Q&A Session",
            'start_datetime' => Carbon::now()->addDays(14)->setTime(10, 0),
            'end_datetime' => Carbon::now()->addDays(14)->setTime(12, 0),
            'duration_minutes' => 120,
            'location_id' => $location->id,
            'capacity' => 30,
            'is_paid' => false,
            'adult_price' => 0,
            'child_price' => 0,
            'concession_price' => 0,
            'status' => 'active',
        ]);

        // Paid Event - Afternoon Tea
        Event::create([
            'title' => 'Victorian Afternoon Tea Experience',
            'description' => 'Step back in time with our Victorian Afternoon Tea experience. Enjoy freshly baked scones, delicate finger sandwiches, and exquisite pastries served in our beautifully restored Great Hall. Learn about Victorian tea culture while savoring traditional recipes from the era.',
            'itinerary' => "2:00 PM - Arrival and Seating\n2:15 PM - Tea Service Begins\n2:30 PM - Victorian Tea Culture Talk\n3:30 PM - Free Time to Explore\n4:00 PM - Event Concludes",
            'start_datetime' => Carbon::now()->addDays(21)->setTime(14, 0),
            'end_datetime' => Carbon::now()->addDays(21)->setTime(16, 0),
            'duration_minutes' => 120,
            'location_id' => $location->id,
            'capacity' => 40,
            'is_paid' => true,
            'adult_price' => 25.00,
            'child_price' => 15.00,
            'concession_price' => 20.00,
            'status' => 'active',
        ]);

        // Free Event - Art Exhibition Opening
        Event::create([
            'title' => 'Contemporary Art Exhibition Opening',
            'description' => 'Be among the first to view our new contemporary art exhibition featuring local artists. The exhibition explores the theme of "Heritage Reimagined" with modern interpretations of historical themes. Light refreshments will be served.',
            'itinerary' => "6:00 PM - Doors Open\n6:15 PM - Welcome Speech\n6:30 PM - Artist Talk\n7:00 PM - Free Viewing\n8:00 PM - Event Ends",
            'start_datetime' => Carbon::now()->addDays(7)->setTime(18, 0),
            'end_datetime' => Carbon::now()->addDays(7)->setTime(20, 0),
            'duration_minutes' => 120,
            'location_id' => $location->id,
            'capacity' => 50,
            'is_paid' => false,
            'adult_price' => 0,
            'child_price' => 0,
            'concession_price' => 0,
            'status' => 'active',
        ]);

        // Paid Event - History Workshop
        Event::create([
            'title' => 'Medieval History Workshop',
            'description' => 'An immersive workshop exploring medieval life at Delapré Abbey. Participants will learn about medieval crafts, try on period costumes, and participate in hands-on activities. Perfect for history enthusiasts of all ages.',
            'itinerary' => "10:00 AM - Introduction to Medieval Life\n10:30 AM - Costume Try-on Session\n11:00 AM - Medieval Crafts Workshop\n12:00 PM - Medieval Games\n12:30 PM - Closing and Q&A",
            'start_datetime' => Carbon::now()->addDays(28)->setTime(10, 0),
            'end_datetime' => Carbon::now()->addDays(28)->setTime(13, 0),
            'duration_minutes' => 180,
            'location_id' => $location->id,
            'capacity' => 25,
            'is_paid' => true,
            'adult_price' => 18.00,
            'child_price' => 12.00,
            'concession_price' => 15.00,
            'status' => 'active',
        ]);

        // Free Event - Garden Walk
        Event::create([
            'title' => 'Spring Garden Walk',
            'description' => 'Explore the beautiful gardens of Delapré Abbey with our head gardener. Learn about the historic planting schemes, seasonal highlights, and ongoing conservation efforts. A peaceful way to connect with nature and history.',
            'itinerary' => "2:00 PM - Meet at Main Entrance\n2:10 PM - Walled Garden Tour\n2:40 PM - Wildflower Meadow\n3:10 PM - Tree Trail\n3:30 PM - Concludes at Tea Room",
            'start_datetime' => Carbon::now()->addDays(10)->setTime(14, 0),
            'end_datetime' => Carbon::now()->addDays(10)->setTime(15, 30),
            'duration_minutes' => 90,
            'location_id' => $location->id,
            'capacity' => 20,
            'is_paid' => false,
            'adult_price' => 0,
            'child_price' => 0,
            'concession_price' => 0,
            'status' => 'active',
        ]);

        $this->command->info('✓ Created 5 sample events (mix of free and paid)');
    }
}


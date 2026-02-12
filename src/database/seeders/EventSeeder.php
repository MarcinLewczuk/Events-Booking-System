<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Location;
use App\Models\Category;
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

        // Get event categories
        $yogaCategory = Category::where('name', 'Yoga')->first();
        $artGalleryCategory = Category::where('name', 'Art gallery')->first();
        $historyCategory = Category::where('name', 'History')->first();
        $oktoberfestCategory = Category::where('name', 'Oktoberfest')->first();
        $summerRaveCategory = Category::where('name', 'Summertime rave')->first();
        $bigScreenCategory = Category::where('name', 'Big screen event')->first();
        $workshopCategory = Category::where('name', 'Workshops')->first();
        $heritageTourCategory = Category::where('name', 'Heritage tours')->first();
        $familyCategory = Category::where('name', 'Family activities')->first();

        // Yoga Event
        Event::create([
            'title' => 'Sunrise Yoga in the Walled Garden',
            'description' => 'Start your day with a peaceful yoga session in our historic walled garden. Suitable for all levels, this outdoor class combines gentle movement with the tranquil atmosphere of Delapré Abbey. Bring your own mat.',
            'itinerary' => "7:00 AM - Welcome & Warm-up\n7:15 AM - Yoga Flow Session\n8:00 AM - Meditation & Cool Down\n8:15 AM - Tea & Light Refreshments",
            'start_datetime' => Carbon::now()->addDays(5)->setTime(7, 0),
            'end_datetime' => Carbon::now()->addDays(5)->setTime(8, 30),
            'duration_minutes' => 90,
            'location_id' => $location->id,
            'category_id' => $yogaCategory->id,
            'capacity' => 25,
            'is_paid' => true,
            'adult_price' => 12.00,
            'child_price' => 0,
            'concession_price' => 10.00,
            'status' => 'active',
        ]);

        // Art Gallery Event
        Event::create([
            'title' => 'Local Artists Exhibition: Northampton Through Time',
            'description' => 'Explore stunning works by local artists inspired by Northampton\'s rich history and heritage. This exhibition features paintings, photography, and mixed media celebrating our city\'s past and present. Free entry.',
            'itinerary' => "10:00 AM - Gallery Opens\n11:00 AM - Artist Talk\n2:00 PM - Curator\'s Tour\n4:00 PM - Gallery Closes",
            'start_datetime' => Carbon::now()->addDays(7)->setTime(10, 0),
            'end_datetime' => Carbon::now()->addDays(7)->setTime(16, 0),
            'duration_minutes' => 360,
            'location_id' => $location->id,
            'category_id' => $artGalleryCategory->id,
            'capacity' => 80,
            'is_paid' => false,
            'adult_price' => 0,
            'child_price' => 0,
            'concession_price' => 0,
            'status' => 'active',
        ]);

        // History Event
        Event::create([
            'title' => 'The Battle of Northampton 1460: A Historical Talk',
            'description' => 'Join local historian Dr. Sarah Matthews for a fascinating talk about the Wars of the Roses battle fought near Delapré Abbey. Discover how this pivotal conflict shaped English history and the abbey\'s role during turbulent times.',
            'itinerary' => "7:00 PM - Welcome & Introduction\n7:15 PM - Main Presentation\n8:15 PM - Q&A Session\n8:45 PM - Close",
            'start_datetime' => Carbon::now()->addDays(14)->setTime(19, 0),
            'end_datetime' => Carbon::now()->addDays(14)->setTime(20, 45),
            'duration_minutes' => 105,
            'location_id' => $location->id,
            'category_id' => $historyCategory->id,
            'capacity' => 60,
            'is_paid' => true,
            'adult_price' => 8.00,
            'child_price' => 5.00,
            'concession_price' => 6.00,
            'status' => 'active',
        ]);

        // Oktoberfest Event
        Event::create([
            'title' => 'Delapré Oktoberfest: Beer, Brats & Bavarian Music',
            'description' => 'Prost! Join us for a traditional Oktoberfest celebration at Delapré Abbey. Enjoy German beers, bratwurst, pretzels, and live Bavarian music in the Abbey gardens. Traditional dress encouraged! Over 18s only.',
            'itinerary' => "5:00 PM - Doors Open\n5:30 PM - Live Band Begins\n6:00 PM - Food Served\n8:00 PM - Beer Stein Holding Competition\n9:30 PM - Event Closes",
            'start_datetime' => Carbon::now()->addDays(35)->setTime(17, 0),
            'end_datetime' => Carbon::now()->addDays(35)->setTime(21, 30),
            'duration_minutes' => 270,
            'location_id' => $location->id,
            'category_id' => $oktoberfestCategory->id,
            'capacity' => 150,
            'is_paid' => true,
            'adult_price' => 22.00,
            'child_price' => 0,
            'concession_price' => 20.00,
            'status' => 'active',
        ]);

        // Summer Rave Event
        Event::create([
            'title' => 'Sunset Sessions: Summer Garden Party',
            'description' => 'Dance the night away at our summer garden party! DJs spinning house, techno, and electronic beats against the stunning backdrop of Delapré Abbey at sunset. Bar, street food vendors, and good vibes. 18+',
            'itinerary' => "6:00 PM - Gates Open\n7:00 PM - DJ Set Begins\n9:00 PM - Sunset Performance\n11:00 PM - Headliner\n1:00 AM - Event Ends",
            'start_datetime' => Carbon::now()->addDays(60)->setTime(18, 0),
            'end_datetime' => Carbon::now()->addDays(60)->setTime(1, 0),
            'duration_minutes' => 420,
            'location_id' => $location->id,
            'category_id' => $summerRaveCategory->id,
            'capacity' => 200,
            'is_paid' => true,
            'adult_price' => 28.00,
            'child_price' => 0,
            'concession_price' => 25.00,
            'status' => 'active',
        ]);

        // Big Screen Event
        Event::create([
            'title' => 'Outdoor Cinema: The Great Gatsby',
            'description' => 'Experience cinema under the stars! Watch the classic film The Great Gatsby on our giant outdoor screen in the Abbey gardens. Bring blankets, deck chairs, and picnics. Hot drinks and snacks available.',
            'itinerary' => "7:30 PM - Gates Open & Picnic Time\n8:30 PM - Film Begins\n11:00 PM - Film Ends",
            'start_datetime' => Carbon::now()->addDays(21)->setTime(19, 30),
            'end_datetime' => Carbon::now()->addDays(21)->setTime(23, 0),
            'duration_minutes' => 210,
            'location_id' => $location->id,
            'category_id' => $bigScreenCategory->id,
            'capacity' => 120,
            'is_paid' => true,
            'adult_price' => 15.00,
            'child_price' => 8.00,
            'concession_price' => 12.00,
            'status' => 'active',
        ]);

        // Workshop Event
        Event::create([
            'title' => 'Medieval Calligraphy Workshop',
            'description' => 'Learn the ancient art of medieval calligraphy with expert tutor Rebecca Holmes. Create beautiful illuminated letters using traditional techniques and pigments. All materials provided. Perfect for beginners.',
            'itinerary' => "10:00 AM - Introduction to Medieval Scripts\n10:30 AM - Hands-on Practice\n12:00 PM - Break with Refreshments\n12:30 PM - Create Your Own Design\n2:00 PM - Workshop Ends",
            'start_datetime' => Carbon::now()->addDays(12)->setTime(10, 0),
            'end_datetime' => Carbon::now()->addDays(12)->setTime(14, 0),
            'duration_minutes' => 240,
            'location_id' => $location->id,
            'category_id' => $workshopCategory->id,
            'capacity' => 15,
            'is_paid' => true,
            'adult_price' => 35.00,
            'child_price' => 25.00,
            'concession_price' => 30.00,
            'status' => 'active',
        ]);

        // Heritage Tour Event
        Event::create([
            'title' => 'Behind the Scenes: Secret Abbey Tour',
            'description' => 'Discover areas rarely open to the public! This exclusive tour takes you into the Abbey\'s hidden corners, including the Victorian servants\' quarters, cellars, and attic spaces. Learn untold stories from 900 years of history.',
            'itinerary' => "2:00 PM - Meet at Reception\n2:15 PM - Cellars & Underground\n2:45 PM - Servants\' Quarters\n3:15 PM - Attic & Roof Space\n3:45 PM - Q&A & Tea",
            'start_datetime' => Carbon::now()->addDays(18)->setTime(14, 0),
            'end_datetime' => Carbon::now()->addDays(18)->setTime(16, 0),
            'duration_minutes' => 120,
            'location_id' => $location->id,
            'category_id' => $heritageTourCategory->id,
            'capacity' => 12,
            'is_paid' => true,
            'adult_price' => 16.00,
            'child_price' => 10.00,
            'concession_price' => 14.00,
            'status' => 'active',
        ]);

        // Family Activities Event
        Event::create([
            'title' => 'Nature Detectives: Family Wildlife Adventure',
            'description' => 'A fun-filled afternoon for families! Explore the Abbey grounds, search for wildlife, build bug hotels, and complete nature challenges. Children must be accompanied by an adult. All ages welcome.',
            'itinerary' => "1:00 PM - Welcome & Activity Packs\n1:15 PM - Wildlife Trail\n2:00 PM - Bug Hotel Building\n2:45 PM - Nature Art\n3:30 PM - Prize Giving & Close",
            'start_datetime' => Carbon::now()->addDays(9)->setTime(13, 0),
            'end_datetime' => Carbon::now()->addDays(9)->setTime(15, 30),
            'duration_minutes' => 150,
            'location_id' => $location->id,
            'category_id' => $familyCategory->id,
            'capacity' => 40,
            'is_paid' => false,
            'adult_price' => 0,
            'child_price' => 0,
            'concession_price' => 0,
            'status' => 'active',
        ]);

        $this->command->info('✓ Created 9 Delapré Abbey events across all categories');
    }
}


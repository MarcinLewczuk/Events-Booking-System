<x-layouts.app>
    @php
        // Sample event data - in reality this would come from a database
        $events = [
            1 => [
                'title' => 'Valentine\'s Murder Mystery',
                'type' => 'SPECIAL EVENT',
                'date' => 'Thursday, 14th February 2026',
                'time' => '7:00 PM - 10:30 PM',
                'location' => 'Great Hall, Delapré Abbey',
                'price' => '£45 per person',
                'description' => 'Step back in time for an evening of mystery and intrigue at Delapré Abbey. When a prominent guest is found dead in the Great Hall, it\'s up to you to solve the crime. Enjoy a three-course dinner while you gather clues, interrogate suspects, and piece together the evidence. Set against the atmospheric backdrop of our 900-year-old abbey, this immersive experience combines history, drama, and fine dining for an unforgettable evening.',
                'includes' => [
                    'Three-course dinner',
                    'Welcome drink on arrival',
                    'Interactive murder mystery experience',
                    'Prize for the winning detective',
                    'Access to abbey grounds'
                ],
                'important' => [
                    'Smart casual dress code',
                    'Suitable for ages 16+',
                    'Event runs for approximately 3.5 hours',
                    'Limited to 50 participants'
                ]
            ],
            2 => [
                'title' => 'Indoor Flea Market',
                'type' => 'EXHIBITION',
                'date' => '31st January - 28th March 2026',
                'time' => 'Saturdays & Sundays, 10:00 AM - 4:00 PM',
                'location' => 'Stable Yard, Delapré Abbey',
                'price' => 'Free entry',
                'description' => 'Browse through a treasure trove of vintage finds, antiques, and unique collectibles at our popular indoor flea market. Local vendors showcase everything from vintage clothing and jewelry to rare books, vinyl records, and home décor. Whether you\'re a seasoned collector or just enjoy hunting for bargains, you\'ll find something special in the atmospheric setting of our historic stable yard.',
                'includes' => [
                    'Free entry to the market',
                    'Over 30 vendors each weekend',
                    'Café and refreshments available',
                    'Free parking',
                    'Accessible facilities'
                ],
                'important' => [
                    'Cash and card payments accepted by most vendors',
                    'Dogs on leads welcome',
                    'Toilets and baby changing facilities available',
                    'No entry fee but some vendors may have minimum spend'
                ]
            ],
            3 => [
                'title' => 'Heritage Crafts Workshop',
                'type' => 'WORKSHOP',
                'date' => 'Tuesday, 18th February 2026',
                'time' => '10:00 AM - 3:00 PM',
                'location' => 'Workshop Room, Delapré Abbey',
                'price' => '£35 per person (materials included)',
                'description' => 'Learn traditional heritage crafts from expert artisans in this hands-on workshop. Discover techniques that have been passed down through generations, including calligraphy, bookbinding, and textile work. You\'ll create your own piece to take home while learning about the historical significance of these crafts. Perfect for beginners and experienced crafters alike, this workshop offers a unique opportunity to connect with the abbey\'s rich artistic heritage.',
                'includes' => [
                    'All materials and tools provided',
                    'Expert tuition from heritage craft specialists',
                    'Light lunch and refreshments',
                    'Take-home craft project',
                    'Workshop certificate'
                ],
                'important' => [
                    'Booking essential - limited to 12 participants',
                    'Suitable for ages 14+',
                    'Wear comfortable clothing',
                    'No previous experience necessary'
                ]
            ],
            4 => [
                'title' => 'Guided Heritage Tour',
                'type' => 'TOUR',
                'date' => 'Thursday, 20th February 2026',
                'time' => 'Tours at 11:00 AM & 2:00 PM',
                'location' => 'Delapré Abbey & Grounds',
                'price' => '£12 adults, £6 children (under 18s)',
                'description' => 'Embark on a fascinating journey through 900 years of history with our expert guides. From its founding as a Cluniac nunnery in 1145 to its current role as a thriving heritage venue, Delapré Abbey has countless stories to tell. Explore the grand rooms, discover hidden architectural details, and hear tales of the people who lived and worked here. The tour includes access to areas not normally open to the public, offering a rare glimpse into the abbey\'s most interesting spaces.',
                'includes' => [
                    '90-minute guided tour',
                    'Access to private rooms',
                    'Historical information booklet',
                    'Complimentary tea/coffee after the tour',
                    'Time to explore the gardens'
                ],
                'important' => [
                    'Comfortable shoes recommended',
                    'Some stairs and uneven surfaces',
                    'Tours run rain or shine',
                    'Booking recommended but walk-ins welcome if space available'
                ]
            ],
            5 => [
                'title' => 'Classical Evening Concert',
                'type' => 'CONCERT',
                'date' => 'Saturday, 22nd February 2026',
                'time' => '7:30 PM - 9:30 PM',
                'location' => 'Great Hall, Delapré Abbey',
                'price' => '£25 per person',
                'description' => 'Experience the magic of live classical music in the stunning setting of our Great Hall. The Northampton Chamber Orchestra performs a selection of beloved classical pieces, from baroque to romantic era compositions. The exceptional acoustics of the historic hall bring new life to these timeless works. Enjoy a glass of wine during the interval as you soak in the atmosphere of this special evening.',
                'includes' => [
                    'Live performance by Northampton Chamber Orchestra',
                    'Glass of wine or soft drink during interval',
                    'Printed programme',
                    'Pre-concert talk (7:00 PM)',
                    'Reserved seating'
                ],
                'important' => [
                    'Doors open at 7:00 PM',
                    'Smart casual attire',
                    'Late entry not permitted once performance begins',
                    'Concession rates available for students and seniors'
                ]
            ],
            6 => [
                'title' => 'Family Fun Day',
                'type' => 'FAMILY EVENT',
                'date' => 'Sunday, 25th February 2026',
                'time' => '10:00 AM - 4:00 PM',
                'location' => 'Delapré Abbey & Gardens',
                'price' => '£8 adults, £5 children, £22 family ticket (2 adults + 2 children)',
                'description' => 'Bring the whole family for a day of adventure and discovery at Delapré Abbey! This action-packed event features treasure hunts through the historic rooms, hands-on craft activities, storytelling sessions about the abbey\'s medieval past, and outdoor games in our beautiful gardens. Children can dress up in period costumes, try their hand at medieval crafts, and even learn a traditional dance. With activities running throughout the day, there\'s something to keep everyone entertained.',
                'includes' => [
                    'All activities and crafts',
                    'Treasure hunt with prizes',
                    'Costume dressing-up',
                    'Storytelling sessions',
                    'Face painting',
                    'Access to all grounds and gardens'
                ],
                'important' => [
                    'Suitable for children aged 3-12',
                    'Children must be accompanied by an adult',
                    'Food and drinks available to purchase',
                    'Weather-dependent activities may move indoors',
                    'Booking recommended'
                ]
            ]
        ];

        $event = $events[$id] ?? $events[1];
    @endphp

    <!-- Breadcrumb -->
    <div class="bg-gray-50 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-primary-600">Home</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('events') }}" class="hover:text-primary-600">What's On</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-900 font-medium">{{ $event['title'] }}</span>
            </div>
        </div>
    </div>

    <!-- Event Header -->
    <div class="relative bg-primary-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <span class="inline-block px-4 py-1 bg-secondary-600 text-white text-sm font-semibold rounded-full mb-4">
                        {{ $event['type'] }}
                    </span>
                    <h1 class="text-5xl font-bold mb-4">{{ $event['title'] }}</h1>
                    <div class="flex flex-wrap gap-6 text-lg">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $event['date'] }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $event['time'] }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ $event['location'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Left Column - Event Details -->
            <div class="lg:col-span-2">
                <!-- Description -->
                <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Event</h2>
                    <p class="text-gray-700 leading-relaxed text-lg">
                        {{ $event['description'] }}
                    </p>
                </div>

                <!-- What's Included -->
                <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">What's Included</h2>
                    <ul class="space-y-3">
                        @foreach($event['includes'] as $item)
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-primary-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Important Information -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Important Information</h2>
                    <ul class="space-y-3">
                        @foreach($event['important'] as $item)
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-secondary-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-gray-700">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Right Column - Booking Widget -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-8 sticky top-24">
                    <div class="text-center mb-6">
                        <div class="text-4xl font-bold text-primary-700 mb-2">{{ $event['price'] }}</div>
                        @if(strpos($event['price'], 'Free') === false)
                        <p class="text-gray-600 text-sm">Per ticket</p>
                        @endif
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="border-t border-b border-gray-200 py-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-700 font-medium">Date</span>
                                <span class="text-gray-900">{{ $event['date'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-700 font-medium">Time</span>
                                <span class="text-gray-900">{{ $event['time'] }}</span>
                            </div>
                        </div>
                    </div>

                    <button class="w-full bg-secondary-500 hover:bg-secondary-600 text-gray-900 py-4 rounded-lg transition font-bold text-lg mb-4 shadow-lg">
                        Book Tickets
                    </button>

                    <button class="w-full border-2 border-primary-500 text-primary-500 py-3 rounded-lg hover:bg-primary-50 transition font-semibold">
                        Add to Calendar
                    </button>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-900 mb-3">Share this event</h3>
                        <div class="flex gap-2">
                            <button class="flex-1 bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                                Facebook
                            </button>
                            <button class="flex-1 bg-sky-500 text-white py-2 rounded hover:bg-sky-600 transition">
                                Twitter
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                        <p class="text-sm text-gray-600 mb-3">Need help with booking?</p>
                        <a href="{{ route('contact') }}" class="text-primary-600 hover:text-primary-700 font-semibold">
                            Contact Us →
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Related Events -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">More Events You Might Like</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @for($i = 1; $i <= 3; $i++)
                    @php
                        $relatedId = ($id % 6) + $i;
                        if ($relatedId > 6) $relatedId = $relatedId - 6;
                        if ($relatedId == $id) $relatedId = ($relatedId % 6) + 1;
                        $relatedEvent = $events[$relatedId] ?? $events[1];
                    @endphp
                    <a href="{{ route('events.show', $relatedId) }}" class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition group">
                        <div class="relative h-48 bg-gradient-to-br from-primary-200 to-primary-300">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-16 h-16 text-primary-600 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-sm text-primary-600 font-semibold mb-2">{{ $relatedEvent['type'] }}</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-primary-600 transition">
                                {{ $relatedEvent['title'] }}
                            </h3>
                            <div class="text-gray-600 text-sm">{{ $relatedEvent['date'] }}</div>
                        </div>
                    </a>
                @endfor
            </div>
        </div>
    </div>

</x-layouts.app>

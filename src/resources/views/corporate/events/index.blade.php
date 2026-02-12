<x-layouts.app>
    <!-- Hero Section
    <div class="relative bg-primary-500 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-5xl font-bold mb-4">What's On</h1>
            <p class="text-xl text-primary-100 max-w-2xl">
                Discover upcoming events, exhibitions, and experiences at Delapré Abbey
            </p>
        </div>
    </div>-->

    <!-- Events Carousel Section -->
    @if($upcomingEvents && $upcomingEvents->count() > 0)
    <div class="relative bg-primary-900 overflow-hidden" x-data="{ 
        currentSlide: 0, 
        totalSlides: {{ $upcomingEvents->count() }}
    }">
        <!-- Carousel Items -->
        <div class="relative h-[600px]">
            @foreach($upcomingEvents as $index => $event)
                <div x-show="currentSlide === {{ $index }}"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 transform translate-x-full"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform -translate-x-full"
                     class="absolute inset-0">
                    
                    <!-- Background - Event Image or Black -->
                    @if($event->primary_image)
                        <img src="{{ asset('storage/' . $event->primary_image) }}" 
                             alt="{{ $event->title }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-black"></div>
                    @endif
                    
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-transparent"></div>
                    
                    <!-- Content -->
                    <div class="absolute inset-0 flex items-center">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                            <div class="max-w-2xl">
                                <span class="inline-block px-4 py-1 bg-secondary-500 text-gray-900 text-sm font-semibold rounded-full mb-4">
                                    Upcoming Event
                                </span>
                                <h2 class="text-5xl font-bold text-white mb-4">{{ $event->title }}</h2>
                                
                                @if($event->description)
                                    <p class="text-xl text-gray-200 mb-6 line-clamp-3">{{ $event->description }}</p>
                                @endif
                                
                                <div class="flex items-center text-white mb-8 space-x-6 flex-wrap">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-lg">{{ $event->start_datetime->format('F j, Y') }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-lg">{{ $event->start_datetime->format('g:i A') }}</span>
                                    </div>
                                    @if($event->location)
                                        <div class="flex items-center">
                                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="text-lg">{{ $event->location->name }}</span>
                                        </div>
                                    @endif
                                </div>

                                <a href="{{ route('events.show', $event) }}" 
                                   class="inline-block bg-secondary-500 hover:bg-secondary-600 text-gray-900 font-bold py-4 px-8 rounded-lg transition text-lg">
                                    Learn More →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Carousel Controls -->
        <button @click="currentSlide = currentSlide === 0 ? totalSlides - 1 : currentSlide - 1"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/20 hover:bg-white/40 text-white p-3 rounded-full backdrop-blur-sm transition z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button @click="currentSlide = currentSlide === totalSlides - 1 ? 0 : currentSlide + 1"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/20 hover:bg-white/40 text-white p-3 rounded-full backdrop-blur-sm transition z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <!-- Carousel Indicators -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
            @foreach($upcomingEvents as $index => $event)
                <button @click="currentSlide = {{ $index }}"
                        :class="currentSlide === {{ $index }} ? 'bg-white' : 'bg-white/50'"
                        class="w-3 h-3 rounded-full transition"></button>
            @endforeach
        </div>

        <!-- Auto-advance carousel -->
        <div x-init="setInterval(() => { currentSlide = currentSlide === totalSlides - 1 ? 0 : currentSlide + 1 }, 5000)"></div>
    </div>
    @endif

    <!-- Filter Bar -->
    <div class="bg-white border-b sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-wrap gap-4 items-center">
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    <option>All Events</option>
                    <option>Exhibitions</option>
                    <option>Workshops</option>
                    <option>Tours</option>
                    <option>Special Events</option>
                </select>
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    <option>All Dates</option>
                    <option>This Week</option>
                    <option>This Month</option>
                    <option>Next Month</option>
                </select>
                <button class="px-6 py-2 bg-secondary-500 hover:bg-secondary-600 text-gray-900 font-bold rounded-lg transition shadow-lg">
                    Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Events Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Event Card 1 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition group">
                <div class="relative h-64 bg-gradient-to-br from-primary-200 to-primary-300">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-24 h-24 text-primary-600 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="absolute top-4 left-4 bg-secondary-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        14 FEB
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-sm text-primary-600 font-semibold mb-2">SPECIAL EVENT</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition">
                        Valentine's Murder Mystery
                    </h3>
                    <p class="text-gray-600 mb-4">
                        An evening of intrigue, dinner, and mystery set in the historic abbey. Can you solve the crime?
                    </p>
                    <div class="flex items-center text-gray-500 text-sm mb-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        7:00 PM - 10:30 PM
                    </div>
                    <a href="{{ route('events.show', 1) }}" class="inline-block w-full text-center px-6 py-3 bg-secondary-500 hover:bg-secondary-600 text-gray-900 rounded-lg transition font-bold shadow-lg">
                        Book Now
                    </a>
                </div>
            </div>

            <!-- Event Card 2 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition group">
                <div class="relative h-64 bg-gradient-to-br from-secondary-200 to-secondary-300">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-24 h-24 text-secondary-700 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div class="absolute top-4 left-4 bg-secondary-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        ONGOING
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-sm text-secondary-600 font-semibold mb-2">EXHIBITION</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition">
                        Indoor Flea Market
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Browse unique treasures and vintage finds at our monthly indoor market. Something for everyone!
                    </p>
                    <div class="flex items-center text-gray-500 text-sm mb-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        31 Jan - 28 Mar 2026
                    </div>
                    <a href="{{ route('events.show', 2) }}" class="inline-block w-full text-center px-6 py-3 bg-secondary-500 hover:bg-secondary-600 text-gray-900 rounded-lg transition font-bold shadow-lg">
                        View Details
                    </a>
                </div>
            </div>

            <!-- Event Card 3 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition group">
                <div class="relative h-64 bg-gradient-to-br from-green-200 to-green-300">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-24 h-24 text-green-700 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="absolute top-4 left-4 bg-primary-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        18 FEB
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-sm text-green-600 font-semibold mb-2">WORKSHOP</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition">
                        Heritage Crafts Workshop
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Learn traditional crafting techniques in a hands-on workshop set in our historic stables.
                    </p>
                    <div class="flex items-center text-gray-500 text-sm mb-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        10:00 AM - 3:00 PM
                    </div>
                    <a href="{{ route('events.show', 3) }}" class="inline-block w-full text-center px-6 py-3 bg-secondary-500 hover:bg-secondary-600 text-gray-900 rounded-lg transition font-bold shadow-lg">
                        Book Now
                    </a>
                </div>
            </div>

            <!-- Event Card 4 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition group">
                <div class="relative h-64 bg-gradient-to-br from-blue-200 to-blue-300">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-24 h-24 text-blue-700 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="absolute top-4 left-4 bg-secondary-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        20 FEB
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-sm text-blue-600 font-semibold mb-2">TOUR</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition">
                        Guided Heritage Tour
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Explore 900 years of history with our expert guides. Discover hidden stories of the abbey.
                    </p>
                    <div class="flex items-center text-gray-500 text-sm mb-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        11:00 AM & 2:00 PM
                    </div>
                    <a href="{{ route('events.show', 4) }}" class="inline-block w-full text-center px-6 py-3 bg-secondary-500 hover:bg-secondary-600 text-gray-900 rounded-lg transition font-bold shadow-lg">
                        Book Now
                    </a>
                </div>
            </div>

            <!-- Event Card 5 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition group">
                <div class="relative h-64 bg-gradient-to-br from-purple-200 to-purple-300">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-24 h-24 text-purple-700 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                        </svg>
                    </div>
                    <div class="absolute top-4 left-4 bg-secondary-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        22 FEB
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-sm text-purple-600 font-semibold mb-2">CONCERT</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition">
                        Classical Evening Concert
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Enjoy an evening of classical music performed in the stunning Great Hall. Refreshments included.
                    </p>
                    <div class="flex items-center text-gray-500 text-sm mb-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        7:30 PM - 9:30 PM
                    </div>
                    <a href="{{ route('events.show', 5) }}" class="inline-block w-full text-center px-6 py-3 bg-secondary-500 hover:bg-secondary-600 text-gray-900 rounded-lg transition font-bold shadow-lg">
                        Book Now
                    </a>
                </div>
            </div>

            <!-- Event Card 6 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition group">
                <div class="relative h-64 bg-gradient-to-br from-yellow-200 to-yellow-300">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-24 h-24 text-yellow-700 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="absolute top-4 left-4 bg-primary-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        25 FEB
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-sm text-yellow-600 font-semibold mb-2">FAMILY EVENT</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition">
                        Family Fun Day
                    </h3>
                    <p class="text-gray-600 mb-4">
                        A day packed with activities for all ages. Treasure hunts, crafts, and stories from history!
                    </p>
                    <div class="flex items-center text-gray-500 text-sm mb-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        10:00 AM - 4:00 PM
                    </div>
                    <a href="{{ route('events.show', 6) }}" class="inline-block w-full text-center px-6 py-3 bg-secondary-500 hover:bg-secondary-600 text-gray-900 rounded-lg transition font-bold shadow-lg">
                        Book Now
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Call to Action -->
    <div class="bg-gray-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Want to stay updated?</h2>
            <p class="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">
                Subscribe to our newsletter to receive updates about upcoming events and special offers
            </p>
            <button class="px-8 py-3 bg-secondary-500 hover:bg-secondary-600 text-gray-900 rounded-lg transition font-bold shadow-lg">
                Subscribe Now
            </button>
        </div>
    </div>

</x-layouts.app>

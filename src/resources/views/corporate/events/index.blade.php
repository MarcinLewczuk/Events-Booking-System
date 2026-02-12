<x-layouts.app>
    <!-- Hero Section -->
    <!-- <div class="relative bg-primary-400 text-white">
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
            <form method="GET" action="{{ route('events') }}" class="flex flex-wrap gap-4 items-center">
                <!-- Category Filter -->
                <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Date Filter -->
                <select name="date_filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400">
                    <option value="">All Dates</option>
                    <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>This Week</option>
                    <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="next_month" {{ request('date_filter') == 'next_month' ? 'selected' : '' }}>Next Month</option>
                    <option value="upcoming" {{ request('date_filter') == 'upcoming' ? 'selected' : '' }}>All Upcoming</option>
                </select>

                <!-- Custom Date Range -->
                <div class="flex gap-2 items-center">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 cursor-pointer">
                    <span class="text-gray-500">to</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 cursor-pointer">
                </div>

                <!-- Filter Button -->
                <button type="submit" class="px-6 py-2 bg-secondary-500 hover:bg-secondary-600 text-gray-900 font-bold rounded-lg transition shadow-lg">
                    Apply Filters
                </button>

                <!-- Clear Filters -->
                @if(request()->hasAny(['category', 'date_filter', 'start_date', 'end_date']))
                    <a href="{{ route('events') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                        Clear Filters
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Events Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($events->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No events found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your filters to see more results.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($events as $event)
                    <!-- Event Card -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition group">
                        <div class="relative h-64 bg-gradient-to-br from-teal-light-400 to-primary-400">
                            @if($event->primary_image)
                                <img src="{{ asset('storage/' . $event->primary_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-24 h-24 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4 bg-secondary-500 text-gray-900 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $event->start_datetime->format('d M') }}
                            </div>
                            @if($event->is_paid)
                                <div class="absolute top-4 right-4 bg-primary-700 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    From £{{ number_format($event->child_price ?? $event->adult_price, 2) }}
                                </div>
                            @else
                                <div class="absolute top-4 right-4 bg-green-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    FREE
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            @if($event->category)
                                <div class="text-sm text-primary-400 font-semibold mb-2 uppercase">{{ $event->category->name }}</div>
                            @endif
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-400 transition">
                                {{ $event->title }}
                            </h3>
                            <p class="text-gray-600 mb-4 line-clamp-2">
                                {{ Str::limit($event->description, 100) }}
                            </p>
                            <div class="flex items-center text-gray-500 text-sm mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $event->start_datetime->format('l, F j, Y') }}
                            </div>
                            <div class="flex items-center text-gray-500 text-sm mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $event->start_datetime->format('g:i A') }} - {{ $event->end_datetime->format('g:i A') }}
                            </div>
                            <div class="flex items-center text-gray-500 text-sm mb-4">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $event->location->name }}
                            </div>
                            
                            @if($event->is_fully_booked)
                                <div class="w-full text-center px-6 py-3 bg-gray-300 text-gray-600 rounded-lg font-bold cursor-not-allowed">
                                    Fully Booked
                                </div>
                            @elseif($event->remaining_spaces <= 5)
                                <div class="mb-2 text-sm text-orange-600 font-semibold">
                                    Only {{ $event->remaining_spaces }} spaces left!
                                </div>
                                <a href="{{ route('events.book', $event->id) }}" class="inline-block w-full text-center px-6 py-3 bg-secondary-500 hover:bg-secondary-600 text-gray-900 rounded-lg transition font-bold shadow-lg">
                                    Book Now
                                </a>
                            @else
                                <a href="{{ route('events.book', $event->id) }}" class="inline-block w-full text-center px-6 py-3 bg-secondary-500 hover:bg-secondary-600 text-gray-900 rounded-lg transition font-bold shadow-lg">
                                    Book Now
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $events->links() }}
            </div>
        @endif
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

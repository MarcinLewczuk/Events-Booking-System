<x-layouts.app>
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
                <span class="text-gray-900 font-medium">{{ $event->title }}</span>
            </div>
        </div>
    </div>

    <!-- Event Header -->
    <div class="relative bg-primary-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    @if($event->category)
                    <span class="inline-block px-4 py-1 bg-secondary-600 text-white text-sm font-semibold rounded-full mb-4">
                        {{ strtoupper($event->category->name) }}
                    </span>
                    @endif
                    <h1 class="text-5xl font-bold mb-4">{{ $event->title }}</h1>
                    <div class="flex flex-wrap gap-6 text-lg">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $event->start_datetime->format('l, jS F Y') }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $event->start_datetime->format('g:i A') }} - {{ $event->end_datetime->format('g:i A') }}
                        </div>
                        @if($event->location)
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ $event->location->name }}, Delapré Abbey
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Image Carousel -->
    @php
        // Gather all images (primary + gallery)
        $allImages = [];
        if ($event->primary_image) {
            $allImages[] = $event->primary_image;
        }
        if ($event->gallery_images) {
            $galleryImages = is_string($event->gallery_images) ? json_decode($event->gallery_images, true) : $event->gallery_images;
            if (is_array($galleryImages)) {
                $allImages = array_merge($allImages, $galleryImages);
            }
        }
        $hasImages = count($allImages) > 0;
        $imageCount = count($allImages);
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
        @if($hasImages)
            <!-- Image Carousel -->
            <div class="relative h-96 rounded-2xl overflow-hidden shadow-2xl" x-data="{ 
                currentSlide: 0, 
                totalSlides: {{ $imageCount }}
            }">
                <!-- Carousel Images -->
                @foreach($allImages as $index => $imagePath)
                    <div x-show="currentSlide === {{ $index }}"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute inset-0">
                        <img src="{{ asset('storage/' . $imagePath) }}" 
                             alt="{{ $event->title }} - Image {{ $index + 1 }}" 
                             class="w-full h-full object-cover">
                    </div>
                @endforeach

                @if($imageCount > 1)
                    <!-- Previous Button -->
                    <button @click="currentSlide = currentSlide === 0 ? totalSlides - 1 : currentSlide - 1"
                            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-3 rounded-full backdrop-blur-sm transition z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <!-- Next Button -->
                    <button @click="currentSlide = currentSlide === totalSlides - 1 ? 0 : currentSlide + 1"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-3 rounded-full backdrop-blur-sm transition z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <!-- Carousel Indicators -->
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
                        @foreach($allImages as $index => $imagePath)
                            <button @click="currentSlide = {{ $index }}"
                                    :class="currentSlide === {{ $index }} ? 'bg-white' : 'bg-white/50'"
                                    class="w-2.5 h-2.5 rounded-full transition"></button>
                        @endforeach
                    </div>

                    <!-- Image Counter -->
                    <div class="absolute top-4 right-4 bg-black/60 text-white px-3 py-1.5 rounded-full text-sm font-medium backdrop-blur-sm z-10">
                        <span x-text="currentSlide + 1"></span> / {{ $imageCount }}
                    </div>
                @endif
            </div>
        @else
            <!-- Placeholder gradient with icon (no images) -->
            <div class="relative h-96 rounded-2xl overflow-hidden shadow-2xl">
                <div class="w-full h-full bg-gradient-to-br from-primary-400 via-teal-light-400 to-secondary-400 flex items-center justify-center">
                    <div class="text-center text-white">
                        <svg class="w-32 h-32 mx-auto mb-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-2xl font-semibold opacity-80">{{ $event->title }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Left Column - Event Details -->
            <div class="lg:col-span-2">
                <!-- Description -->
                <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Event</h2>
                    <div class="text-gray-700 leading-relaxed text-lg whitespace-pre-line">
                        {{ $event->description }}
                    </div>
                </div>

                @if($event->itinerary)
                <!-- Itinerary -->
                <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Event Details</h2>
                    <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $event->itinerary }}
                    </div>
                </div>
                @endif

                <!-- Event Information -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Event Information</h2>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-primary-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span class="text-gray-700">Capacity: {{ $event->capacity }} people</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-primary-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-gray-700">Duration: {{ $event->duration_minutes }} minutes</span>
                        </li>
                        @if($event->location)
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-primary-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span class="text-gray-700">{{ $event->location->name }} - {{ $event->location->address }}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Right Column - Booking Widget -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-8 sticky top-24">
                    <div class="text-center mb-6">
                        @if($event->is_paid)
                            @if($event->adult_price > 0)
                                <div class="text-4xl font-bold text-primary-700 mb-2">£{{ number_format($event->adult_price, 2) }}</div>
                                <p class="text-gray-600 text-sm">Adult ticket</p>
                                @if($event->child_price > 0)
                                    <p class="text-sm text-gray-500 mt-2">Child: £{{ number_format($event->child_price, 2) }}</p>
                                @endif
                                @if($event->concession_price > 0)
                                    <p class="text-sm text-gray-500">Concession: £{{ number_format($event->concession_price, 2) }}</p>
                                @endif
                            @else
                                <div class="text-4xl font-bold text-primary-700 mb-2">Paid Event</div>
                                <p class="text-gray-600 text-sm">See pricing details</p>
                            @endif
                        @else
                            <div class="text-4xl font-bold text-primary-700 mb-2">Free Entry</div>
                            <p class="text-gray-600 text-sm">Booking recommended</p>
                        @endif
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="border-t border-b border-gray-200 py-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-700 font-medium">Date</span>
                                <span class="text-gray-900">{{ $event->start_datetime->format('j M Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-700 font-medium">Time</span>
                                <span class="text-gray-900">{{ $event->start_datetime->format('g:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('events.book', $event->id) }}" class="block w-full bg-secondary-500 hover:bg-secondary-600 text-gray-900 py-4 rounded-lg transition font-bold text-lg mb-4 shadow-lg text-center">
                        Book Tickets
                    </a>

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


                </div>
            </div>

        </div>
    </div>

    <!-- Related Events -->
    @php
        $relatedEvents = \App\Models\Event::with(['category', 'location'])
            ->where('id', '!=', $event->id)
            ->where('start_datetime', '>', now())
            ->when($event->category_id, function($query) use ($event) {
                $query->where('category_id', $event->category_id);
            })
            ->orderBy('start_datetime')
            ->limit(3)
            ->get();
        
        // If not enough events in same category, fill with other events
        if ($relatedEvents->count() < 3) {
            $extraEvents = \App\Models\Event::with(['category', 'location'])
                ->where('id', '!=', $event->id)
                ->where('start_datetime', '>', now())
                ->whereNotIn('id', $relatedEvents->pluck('id'))
                ->orderBy('start_datetime')
                ->limit(3 - $relatedEvents->count())
                ->get();
            $relatedEvents = $relatedEvents->merge($extraEvents);
        }
    @endphp

    @if($relatedEvents->count() > 0)
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">More Events You Might Like</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($relatedEvents as $relatedEvent)
                    <a href="{{ route('events.show', $relatedEvent->id) }}" class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition group">
                        <div class="relative h-48 bg-gradient-to-br from-primary-200 to-primary-300">
                            @if($relatedEvent->primary_image)
                                <img src="{{ asset('storage/' . $relatedEvent->primary_image) }}" alt="{{ $relatedEvent->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-primary-600 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            @if($relatedEvent->category)
                                <div class="text-sm text-primary-600 font-semibold mb-2">{{ strtoupper($relatedEvent->category->name) }}</div>
                            @endif
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-primary-600 transition">
                                {{ $relatedEvent->title }}
                            </h3>
                            <div class="text-gray-600 text-sm">{{ $relatedEvent->start_datetime->format('l, jS F Y') }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</x-layouts.app>

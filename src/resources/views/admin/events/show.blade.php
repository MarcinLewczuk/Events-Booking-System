<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            
            <!-- Header with Back Button -->
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('admin.events.index') }}" class="inline-flex items-center text-primary-700 hover:text-primary-800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Events
                </a>
            </div>

            <!-- Title Section -->
            <div class="bg-white rounded-lg shadow-lg p-8 border-l-4 border-primary-900 mb-8">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div class="flex items-start gap-6">
                        @if($event->primary_image)
                            <img src="{{ asset('storage/' . $event->primary_image) }}" 
                                 alt="{{ $event->title }}"
                                 class="w-24 h-24 rounded-lg object-cover border border-gray-300 shadow-md">
                        @else
                            <div class="w-24 h-24 bg-gradient-to-br from-primary-600 to-primary-700 rounded-lg flex items-center justify-center">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $event->title }}</h1>
                            <div class="flex items-center gap-3 mt-3">
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                    @switch($event->status)
                                        @case('draft')
                                            bg-yellow-100 text-yellow-800
                                            @break
                                        @case('active')
                                            @if(now() < $event->start_datetime)
                                                bg-green-100 text-green-800
                                            @else
                                                bg-blue-100 text-blue-800
                                            @endif
                                            @break
                                        @case('cancelled')
                                            bg-red-100 text-red-800
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800
                                    @endswitch
                                ">
                                    @switch($event->status)
                                        @case('draft')
                                            Draft
                                            @break
                                        @case('active')
                                            @if(now() < $event->start_datetime)
                                                Upcoming
                                            @else
                                                Ongoing
                                            @endif
                                            @break
                                        @default
                                            {{ ucfirst($event->status) }}
                                    @endswitch
                                </span>
                                <p class="text-gray-600 text-sm">{{ $event->category->name ?? 'No Category' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.event-breakdown.show', $event) }}" 
                           class="inline-flex items-center px-6 py-2 text-white font-medium rounded-lg transition" style="background-color: #247a7c;">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            View Breakdown
                        </a>
                        <a href="{{ route('admin.events.edit', $event) }}" 
                           class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Event
                        </a>
                    </div>
                </div>
            </div>

            <!-- Key Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Bookings -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-primary-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Bookings</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_bookings'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Tickets Booked -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Tickets Booked</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_tickets'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">of {{ $event->capacity }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m-4 0v2m4 0a2 2 0 110 4H9a2 2 0 110-4h6a2 2 0 100-4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Remaining Capacity -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Available Spaces</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['remaining_capacity'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ round(($stats['remaining_capacity'] / $event->capacity) * 100) }}% available</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Revenue (if paid event) -->
                @if($event->is_paid)
                    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-600">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1">£{{ number_format($stats['booking_value'], 2) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content Area -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Description -->
                    <div class="bg-white rounded-lg shadow-sm p-8 border border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Event Description
                        </h2>
                        <p class="text-gray-700 leading-relaxed">{{ $event->description }}</p>
                        
                        @if($event->tags && $event->tags->isNotEmpty())
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <h3 class="text-sm font-semibold text-gray-600 mb-2">Tags</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($event->tags as $tag)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-700">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Itinerary (if available) -->
                    @if($event->itinerary)
                        <div class="bg-white rounded-lg shadow-sm p-8 border border-gray-200">
                            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Event Itinerary
                            </h2>
                            <div class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $event->itinerary }}</div>
                        </div>
                    @endif

                    <!-- Pricing (if paid) -->
                    @if($event->is_paid)
                        <div class="bg-white rounded-lg shadow-sm p-8 border border-gray-200">
                            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                                <svg class="w-5 h-5 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Ticket Pricing
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @if($event->adult_price)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-sm text-gray-600 font-medium">Adult</p>
                                        <p class="text-2xl font-bold text-gray-900 mt-1">£{{ number_format($event->adult_price, 2) }}</p>
                                    </div>
                                @endif
                                @if($event->child_price)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-sm text-gray-600 font-medium">Child</p>
                                        <p class="text-2xl font-bold text-gray-900 mt-1">£{{ number_format($event->child_price, 2) }}</p>
                                    </div>
                                @endif
                                @if($event->concession_price)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-sm text-gray-600 font-medium">Concession</p>
                                        <p class="text-2xl font-bold text-gray-900 mt-1">£{{ number_format($event->concession_price, 2) }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Event Details Card -->
                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Event Details</h3>
                        
                        <div class="space-y-5">
                            <!-- Date & Time -->
                            <div class="border-b border-gray-200 pb-4">
                                <p class="text-sm text-gray-600 font-medium mb-1">Start Date & Time</p>
                                <p class="text-gray-900 font-semibold">{{ $event->start_datetime->format('d M Y, g:i A') }}</p>
                            </div>

                            <div class="border-b border-gray-200 pb-4">
                                <p class="text-sm text-gray-600 font-medium mb-1">End Date & Time</p>
                                <p class="text-gray-900 font-semibold">{{ $event->end_datetime->format('d M Y, g:i A') }}</p>
                            </div>

                            <!-- Duration -->
                            <div class="border-b border-gray-200 pb-4">
                                <p class="text-sm text-gray-600 font-medium mb-1">Duration</p>
                                <p class="text-gray-900 font-semibold">
                                    @php
                                        $hours = (int)($event->duration_minutes / 60);
                                        $minutes = $event->duration_minutes % 60;
                                    @endphp
                                    {{ $hours }}h {{ $minutes }}min
                                </p>
                            </div>

                            <!-- Location -->
                            <div class="border-b border-gray-200 pb-4">
                                <p class="text-sm text-gray-600 font-medium mb-1">Location</p>
                                <p class="text-gray-900 font-semibold">{{ $event->location->name ?? 'TBD' }}</p>
                                @if($event->location?->address)
                                    <p class="text-sm text-gray-600 mt-1">{{ $event->location->address }}</p>
                                @endif
                            </div>

                            <!-- Category -->
                            <div class="border-b border-gray-200 pb-4">
                                <p class="text-sm text-gray-600 font-medium mb-1">Category</p>
                                <p class="text-gray-900 font-semibold">{{ $event->category->name ?? 'Uncategorized' }}</p>
                            </div>

                            <!-- Capacity -->
                            <div class="pb-4">
                                <p class="text-sm text-gray-600 font-medium mb-1">Total Capacity</p>
                                <p class="text-gray-900 font-semibold">{{ $event->capacity }} attendees</p>
                            </div>
                        </div>
                    </div>

                    <!-- Created & Updated Info -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 text-sm text-gray-600">
                        <p class="mb-2">
                            <strong>Created:</strong> {{ $event->created_at->format('d M Y, g:i A') }}
                        </p>
                        <p>
                            <strong>Last Updated:</strong> {{ $event->updated_at->format('d M Y, g:i A') }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

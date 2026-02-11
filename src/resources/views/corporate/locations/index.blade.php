<x-layouts.app>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary-700 to-primary-600 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-4xl font-bold mb-2">Our Locations</h1>
                <p class="text-lg text-purple-100">Explore our auction venues and facilities</p>
            </div>
        </div>

        <!-- Location Tabs -->
        <div class="bg-white border-b border-gray-200 sticky top-0 z-10 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex overflow-x-auto -mb-px">
                    @foreach($locations as $location)
                        <a href="{{ route('locations.index', ['location' => $location->id]) }}" 
                           class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition {{ $activeLocation && $activeLocation->id == $location->id ? 'border-primary-700 text-primary-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            {{ $location->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        @if($activeLocation)
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                
                <!-- Location Header with Image -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
                    @if($activeLocation->image_path)
                        <div class="relative h-96 overflow-hidden">
                            <img src="{{ asset('storage/' . $activeLocation->image_path) }}" 
                                 alt="{{ $activeLocation->name }}" 
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                                <h2 class="text-4xl font-bold mb-2">{{ $activeLocation->name }}</h2>
                                @if($activeLocation->address)
                                    <p class="text-lg flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $activeLocation->address }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="bg-gradient-to-r from-primary-700 to-primary-600 p-8 text-white">
                            <h2 class="text-4xl font-bold mb-2">{{ $activeLocation->name }}</h2>
                            @if($activeLocation->address)
                                <p class="text-lg flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $activeLocation->address }}
                                </p>
                            @endif
                        </div>
                    @endif

                    <!-- Location Details -->
                    <div class="p-8">
                        @if($activeLocation->description)
                            <div class="mb-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">About This Location</h3>
                                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $activeLocation->description }}</p>
                            </div>
                        @endif

                        <!-- Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            @if($activeLocation->max_attendance)
                                <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                                    <div class="flex items-center">
                                        <svg class="w-8 h-8 text-primary-700 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm text-gray-600">Max Capacity</p>
                                            <p class="text-2xl font-bold text-primary-700">{{ number_format($activeLocation->max_attendance) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-red-700 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-600">Upcoming Auctions</p>
                                        <p class="text-2xl font-bold text-red-700">{{ $upcomingAuctions->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-primary-50 rounded-lg p-4 border border-primary-200">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-primary-700 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-600">Items at Location</p>
                                        <p class="text-2xl font-bold text-primary-700">{{ number_format($totalItems) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Auctions Section -->
                @if($upcomingAuctions->isNotEmpty())
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Upcoming Auctions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($upcomingAuctions as $auction)
                                <a href="{{ route('auctions.show', $auction) }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                                    <div class="bg-gradient-to-r from-primary-700 to-primary-600 p-4 text-white">
                                        <h4 class="font-bold text-lg">{{ $auction->title }}</h4>
                                        @if($auction->catalogue)
                                            <p class="text-sm text-purple-100">{{ $auction->catalogue->name }}</p>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <div class="flex items-center text-sm text-gray-700 mb-2">
                                            <svg class="w-4 h-4 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $auction->auction_date->format('F j, Y') }}
                                        </div>
                                        <p class="text-xs text-gray-500">Starts at {{ $auction->start_time }}</p>
                                        @if($auction->catalogue)
                                            <p class="text-sm text-gray-600 mt-2">
                                                {{ $auction->catalogue->items->count() }} items
                                            </p>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Top Valuable Items Section -->
                @if($topItems->isNotEmpty())
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-bold text-gray-900">Top Items at This Location</h3>
                            <span class="text-sm text-gray-600">{{ number_format($totalItems) }} total items</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($topItems as $item)
                                <a href="{{ route('items.show', $item) }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                                    <div class="relative aspect-square overflow-hidden bg-gray-200">
                                        @if($item->primaryImage)
                                            <img src="{{ asset('storage/' . $item->primaryImage->path) }}" 
                                                 alt="{{ $item->title }}" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h4 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $item->title }}</h4>
                                        @if($item->category)
                                            <p class="text-xs text-gray-500 mb-2">{{ $item->category->name }}</p>
                                        @endif
                                        @if($item->estimated_price)
                                            <p class="text-lg font-bold text-primary-700">£{{ number_format($item->estimated_price, 2) }}</p>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Locations Available</h3>
                    <p class="text-gray-600">Check back soon for our auction locations.</p>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
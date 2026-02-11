<x-layouts.app>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb -->
            <nav class="mb-6 text-sm">
                <ol class="flex items-center space-x-2 text-gray-500">
                    <li><a href="{{ route('home') }}" class="hover:text-primary-700">Home</a></li>
                    <li>/</li>
                    <li><a href="{{ route('auctions.browse') }}" class="hover:text-primary-700">Auctions</a></li>
                    <li>/</li>
                    <li class="text-gray-900 font-medium">{{ $auction->title }}</li>
                </ol>
            </nav>

            <!-- Auction Header -->
            <div class="bg-gradient-to-r from-primary-900 to-primary-600 rounded-lg shadow-lg p-8 mb-8 text-white">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                        <h1 class="text-4xl font-bold mb-2">{{ $auction->title }}</h1>
                        @if($auction->catalogue)
                            <p class="text-xl text-purple-100 mb-4">Catalogue: {{ $auction->catalogue->name }}</p>
                        @endif
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                            <!-- Date & Time -->
                            <div class="flex items-start">
                                <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-purple-100">Auction Date</p>
                                    <p class="font-semibold">{{ $auction->auction_date->format('l, F j, Y') }}</p>
                                    <p class="text-sm">Starts at {{ $auction->start_time }}</p>
                                </div>
                            </div>

                            <!-- Location -->
                            @if($auction->location)
                                <div class="flex items-start">
                                    <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-purple-100">Location</p>
                                        <p class="font-semibold">{{ $auction->location->name }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Items Count -->
                            <div class="flex items-start">
                                <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-purple-100">Total Items</p>
                                    <p class="font-semibold">{{ $allItems->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Badge & Seat Booking -->
                    <div class="mt-6 lg:mt-0 flex flex-col gap-3">
                        @php
                            $statusColors = [
                                'scheduled' => 'bg-purple-500 text-white',
                                'open' => 'bg-green-500 text-white',
                                'closed' => 'bg-red-500 text-white',
                            ];
                            $statusColor = $statusColors[$auction->status] ?? 'bg-gray-500 text-white';
                        @endphp
                        <div class="inline-flex items-center px-6 py-3 rounded-lg text-lg font-semibold {{ $statusColor }}">
                            {{ ucfirst($auction->status) }}
                        </div>

                        <!-- Seat Booking Button for Approved Customers -->
                        @auth
                            @if(auth()->user()->role === 'customer' && auth()->user()->buyer_approved_status && $auction->location && $auction->location->seating_rows && $auction->auction_date > now())
                                <a href="{{ route('customer.seat-booking.show', $auction) }}" 
                                   class="inline-flex items-center justify-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-lg font-semibold transition duration-200 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    @if($auction->hasUserBookedSeat(auth()->id()))
                                        View My Seat
                                    @else
                                        Book a Seat
                                    @endif
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Personalized Recommendations for Logged-in Users -->
            @auth
                @if($recommendedItems->isNotEmpty())
                    <div class="mb-8 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-6 border-l-4 border-primary-700">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-2 flex items-center">
                            <svg class="w-7 h-7 text-primary-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            Items Matching Your Interests
                        </h2>
                        <p class="text-sm text-gray-600 mb-4">Based on your tag preferences, these items might interest you</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($recommendedItems as $item)
                                <a href="{{ route('items.show', $item) }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden group relative">
                                    <div class="absolute top-2 right-2 z-10 bg-primary-700 text-white text-xs font-bold px-2 py-1 rounded-full">
                                        {{ $item->recommendation_score }} {{ Str::plural('match', $item->recommendation_score) }}
                                    </div>
                                    <div class="relative aspect-square overflow-hidden bg-gray-200">
                                        @if($item->primaryImage)
                                            <img src="{{ asset('storage/' . $item->primaryImage->path) }}" 
                                                 alt="{{ $item->title }}" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            @endauth

            <!-- View Toggle Tabs -->
            <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-2">
                <div class="flex gap-2">
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'bands']) }}" 
                       class="flex-1 px-4 py-3 rounded-md text-center font-medium transition {{ $viewMode === 'bands' ? 'bg-primary-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        By Price Band
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'browse']) }}" 
                       class="flex-1 px-4 py-3 rounded-md text-center font-medium transition {{ $viewMode === 'browse' ? 'bg-primary-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Browse & Filter
                    </a>
                </div>
            </div>

            @if($auction->catalogue)
                @if($viewMode === 'bands')
                    <!-- BANDS VIEW: Items Organized by Band -->
                    <div class="mb-12">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Catalogue Items by Price Band</h2>

                        @forelse($itemsByBand as $bandGroup)
                            @php
                                $band = $bandGroup['band'];
                                $items = $bandGroup['items'];
                                $isHighestBand = $loop->first && $band !== null;
                                $isLowestBand = $loop->last;
                            @endphp

                            <div class="mb-12">
                                <!-- Band Header -->
                                @if($band)
                                    <div class="mb-6 bg-gradient-to-r from-primary-700 to-primary-600 rounded-lg p-4 text-white">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="text-2xl font-bold">{{ $band->name }}</h3>
                                                @if($band->description)
                                                    <p class="text-purple-100 mt-1">{{ $band->description }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm text-purple-100">Price Range</p>
                                                @if($band->max_price > 0)
                                                    <p class="text-xl font-bold">£{{ number_format($band->min_price) }} - £{{ number_format($band->max_price) }}</p>
                                                @else
                                                    <p class="text-xl font-bold">£{{ number_format($band->min_price) }}+</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-6 bg-gray-100 rounded-lg p-4">
                                        <h3 class="text-2xl font-bold text-gray-700">Unassigned Items</h3>
                                    </div>
                                @endif

                                <!-- HIGHEST VALUE BAND - 2 per row with multiple images and full description -->
                                @if($isHighestBand)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        @foreach($items as $item)
                                            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden flex flex-col">
                                                <a href="{{ route('items.show', $item) }}" class="block">
                                                    <!-- Multiple Images - 2x2 Grid -->
                                                    @if($item->images->count() >= 4)
                                                        <div class="grid grid-cols-2 gap-1 p-2">
                                                            @foreach($item->images->take(4) as $image)
                                                                <div class="relative aspect-square overflow-hidden rounded bg-gray-200 group">
                                                                    <img src="{{ asset('storage/' . $image->path) }}" 
                                                                         alt="{{ $item->title }}" 
                                                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @elseif($item->primaryImage)
                                                        <div class="relative aspect-square bg-gray-200 overflow-hidden flex items-center justify-center">
                                                            <img
                                                                src="{{ asset('storage/' . $item->primaryImage->path) }}"
                                                                alt="{{ $item->title }}"
                                                                class="max-w-full max-h-full object-contain"
                                                            >
                                                        </div>
                                                    @else
                                                        <div class="aspect-square bg-gray-200 flex items-center justify-center">
                                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </a>
                                                
                                                <!-- Item Details -->
                                                <div class="p-4 flex flex-col flex-grow">
                                                    <a href="{{ route('items.show', $item) }}" class="group">
                                                        <h4 class="text-lg font-bold text-gray-900 group-hover:text-primary-700 line-clamp-2">{{ $item->title }}</h4>
                                                    </a>
                                                    
                                                    @if($item->category)
                                                        <p class="text-xs text-gray-600 mb-2">{{ $item->category->name }}</p>
                                                    @endif
                                                    
                                                    @if($item->estimated_price)
                                                        <div class="mb-3">
                                                            <p class="text-xs text-gray-500">Estimated Price</p>
                                                            <p class="text-xl font-bold text-primary-700">£{{ number_format($item->estimated_price, 2) }}</p>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($item->detailed_description)
                                                        <div class="text-sm text-gray-700 mb-3 flex-grow">
                                                            <p class="">{{ $item->detailed_description }}</p>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($item->tags->isNotEmpty())
                                                        <div class="flex flex-wrap gap-1 mb-3">
                                                            @foreach($item->tags->take(3) as $tag)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $userTags->contains($tag->id) ? 'bg-purple-100 text-primary-700 border border-primary-700' : 'bg-gray-100 text-gray-700' }}">
                                                                    {{ $tag->name }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    
                                                    <a href="{{ route('items.show', $item) }}" class="inline-flex items-center justify-center px-3 py-2 bg-primary-700 text-white text-sm rounded-lg hover:bg-primary-800 transition-colors mt-auto">
                                                        View Details
                                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                <!-- MIDDLE BANDS - 4 per row with 1 image and short description -->
                                @elseif(!$isLowestBand)
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                        @foreach($items as $item)
                                            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden flex flex-col group">
                                                <a href="{{ route('items.show', $item) }}" class="block">
                                                    <div class="relative h-48 overflow-hidden bg-gray-200">
                                                        @if($item->primaryImage)
                                                            <img src="{{ asset('storage/' . $item->primaryImage->path) }}" 
                                                                 alt="{{ $item->title }}" 
                                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center">
                                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </a>
                                                
                                                <div class="p-4 flex flex-col flex-grow">
                                                    <a href="{{ route('items.show', $item) }}" class="group-hover:text-primary-700">
                                                        <h4 class="font-semibold text-gray-900 mb-2 line-clamp-2 text-base min-h-[3rem]">{{ $item->title }}</h4>
                                                    </a>
                                                    
                                                    @if($item->category)
                                                        <p class="text-xs text-gray-500 mb-2">{{ $item->category->name }}</p>
                                                    @endif
                                                    
                                                    @if($item->short_description)
                                                        <p class="text-sm text-gray-600 mb-3 flex-grow line-clamp-3">{{ $item->short_description }}</p>
                                                    @endif
                                                    
                                                    @if($item->estimated_price)
                                                        <p class="text-lg font-bold text-primary-700 mt-auto">£{{ number_format($item->estimated_price, 2) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                <!-- LOWEST BAND - 6 per row with title and price only -->
                                @else
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                        @foreach($items as $item)
                                            <a href="{{ route('items.show', $item) }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                                                <div class="relative h-32 overflow-hidden bg-gray-200">
                                                    @if($item->primaryImage)
                                                        <img src="{{ asset('storage/' . $item->primaryImage->path) }}" 
                                                             alt="{{ $item->title }}" 
                                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center">
                                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="p-3">
                                                    <h4 class="text-sm font-medium text-gray-900 line-clamp-2 mb-2 min-h-[2.5rem]">{{ $item->title }}</h4>
                                                    @if($item->estimated_price)
                                                        <p class="text-sm font-bold text-primary-700">£{{ number_format($item->estimated_price, 2) }}</p>
                                                    @endif
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                        @empty
                            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Items Found</h3>
                                <p class="text-gray-600">No items match your current filters.</p>
                            </div>
                        @endforelse
                    </div>

                @else
                    <!-- BROWSE VIEW: Filterable Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                        
                        <!-- Filters Sidebar -->
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>
                                
                                <form method="GET" action="{{ route('auctions.show', $auction) }}" id="filterForm">
                                    <input type="hidden" name="view" value="browse">
                                    
                                    <!-- Action Buttons -->
                                    <div class="space-y-2 mb-4">
                                        <button type="submit" class="w-full bg-primary-700 text-white px-4 py-2 rounded-md hover:bg-primary-800 transition">
                                            Apply Filters
                                        </button>
                                        <a href="{{ route('auctions.show', $auction) }}?view=browse" class="block w-full text-center bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition">
                                            Clear All
                                        </a>
                                    </div>

                                    <!-- Search -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                                        <input type="text" name="search" value="{{ $search }}" placeholder="Search items..." 
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    </div>

                                    <!-- Category -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                        <select name="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                                @foreach($category->children as $child)
                                                    <option value="{{ $child->id }}" {{ $categoryId == $child->id ? 'selected' : '' }}>
                                                        &nbsp;&nbsp;→ {{ $child->name }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Location -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                                        <select name="location_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <option value="">All Locations</option>
                                            @foreach($locations as $location)
                                                <option value="{{ $location->id }}" {{ $locationId == $location->id ? 'selected' : '' }}>
                                                    {{ $location->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Creator -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Creator/Artist</label>
                                        <input type="text" name="creator" value="{{ $creator }}" placeholder="e.g., Van Gogh" 
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    </div>

                                    <!-- Price Band -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Band</label>
                                        <select name="band_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <option value="">All Bands</option>
                                            @foreach($allBands as $band)
                                                <option value="{{ $band->id }}" {{ $bandId == $band->id ? 'selected' : '' }}>
                                                    {{ $band->name }} (£{{ number_format($band->min_price) }} - £{{ number_format($band->max_price) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Price Range -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range (£)</label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <input type="number" name="min_price" value="{{ $minPrice }}" placeholder="Min" step="0.01" 
                                                   class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <input type="number" name="max_price" value="{{ $maxPrice }}" placeholder="Max" step="0.01" 
                                                   class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                    </div>

                                    <!-- Year of Creation -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Year of Creation</label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <input type="number" name="year_from" value="{{ $yearFrom }}" placeholder="From" min="1" max="{{ date('Y') }}" 
                                                   class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <input type="number" name="year_to" value="{{ $yearTo }}" placeholder="To" min="1" max="{{ date('Y') }}" 
                                                   class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                    </div>

                                    <!-- Weight -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <input type="number" name="weight_from" value="{{ $weightFrom }}" placeholder="Min" step="0.01" min="0" 
                                                   class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <input type="number" name="weight_to" value="{{ $weightTo }}" placeholder="Max" step="0.01" min="0" 
                                                   class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                    </div>

                                    <!-- Tags -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                                        <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-md p-2 space-y-2">
                                            @foreach($tags as $tag)
                                                <label class="flex items-center">
                                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                                           {{ in_array($tag->id, $tagIds) ? 'checked' : '' }}
                                                           class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 mr-2">
                                                    <span class="text-sm">{{ $tag->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Items Grid -->
                        <div class="lg:col-span-3">
                            <!-- Toolbar -->
                            <div class="bg-white rounded-lg shadow-sm p-4 mb-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="text-sm text-gray-600">
                                    Showing <span class="font-semibold">{{ $allItems->count() }}</span> items
                                </div>

                                <div class="flex items-center gap-4">
                                    <!-- Sort By -->
                                    <form method="GET" action="{{ route('auctions.show', $auction) }}" class="flex items-center gap-2">
                                        <input type="hidden" name="view" value="browse">
                                        @if($search)<input type="hidden" name="search" value="{{ $search }}">@endif
                                        @if($categoryId)<input type="hidden" name="category_id" value="{{ $categoryId }}">@endif
                                        @if($locationId)<input type="hidden" name="location_id" value="{{ $locationId }}">@endif
                                        @if($bandId)<input type="hidden" name="band_id" value="{{ $bandId }}">@endif
                                        @foreach($tagIds as $tagId)<input type="hidden" name="tags[]" value="{{ $tagId }}">@endforeach
                                        @if($minPrice)<input type="hidden" name="min_price" value="{{ $minPrice }}">@endif
                                        @if($maxPrice)<input type="hidden" name="max_price" value="{{ $maxPrice }}">@endif
                                        @if($creator)<input type="hidden" name="creator" value="{{ $creator }}">@endif
                                        @if($yearFrom)<input type="hidden" name="year_from" value="{{ $yearFrom }}">@endif
                                        @if($yearTo)<input type="hidden" name="year_to" value="{{ $yearTo }}">@endif
                                        @if($weightFrom)<input type="hidden" name="weight_from" value="{{ $weightFrom }}">@endif
                                        @if($weightTo)<input type="hidden" name="weight_to" value="{{ $weightTo }}">@endif
                                        
                                        <label class="text-sm font-medium text-gray-700">Sort:</label>
                                        <select name="sort_by" onchange="this.form.submit()" 
                                                class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                            <option value="newest" {{ $sortBy == 'newest' ? 'selected' : '' }}>Newest First</option>
                                            <option value="oldest" {{ $sortBy == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                            <option value="price_asc" {{ $sortBy == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                            <option value="price_desc" {{ $sortBy == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                            <option value="title" {{ $sortBy == 'title' ? 'selected' : '' }}>Title (A-Z)</option>
                                        </select>
                                    </form>
                                </div>
                            </div>

                            <!-- Items Grid -->
                            @if($allItems->isNotEmpty())
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($allItems as $item)
                                        <a href="{{ route('items.show', $item) }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                                            @if($item->primaryImage)
                                                <img src="{{ asset('storage/' . $item->primaryImage->path) }}" 
                                                     alt="{{ $item->title }}" 
                                                     class="w-full aspect-square object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="w-full aspect-square bg-gray-200 flex items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="p-4">
                                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $item->title }}</h3>
                                                @if($item->category)
                                                    <p class="text-xs text-gray-500 mb-2">{{ $item->category->name }}</p>
                                                @endif
                                                @if($item->estimated_price)
                                                    <p class="text-primary-700 font-bold">£{{ number_format($item->estimated_price, 2) }}</p>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Items Found</h3>
                                    <p class="text-gray-600">No items match your current filters. Try adjusting your search criteria.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Catalogue Assigned</h3>
                    <p class="text-gray-600">This auction doesn't have a catalogue assigned yet.</p>
                </div>
            @endif

            <!-- Other Upcoming Auctions -->
            @if($otherAuctions->isNotEmpty())
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Other Upcoming Auctions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($otherAuctions as $otherAuction)
                            <a href="{{ route('auctions.show', $otherAuction) }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                                <div class="bg-gradient-to-r from-primary-700 to-primary-600 p-4 text-white">
                                    <h3 class="font-bold text-lg">{{ $otherAuction->title }}</h3>
                                    @if($otherAuction->catalogue)
                                        <p class="text-sm text-purple-100">{{ $otherAuction->catalogue->name }}</p>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <p class="text-sm text-gray-700 mb-2">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $otherAuction->auction_date->format('M d, Y') }}
                                    </p>
                                    @if($otherAuction->location)
                                        <p class="text-sm text-gray-600">{{ $otherAuction->location->name }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Back Button -->
            <div class="mt-8">
                <x-buttons.secondary href="{{ route('auctions.browse') }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Auctions
                </x-buttons.secondary>
            </div>
        </div>
    </div>
</x-layouts.app>
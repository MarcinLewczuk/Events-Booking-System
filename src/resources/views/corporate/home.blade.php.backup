<x-layouts.app>
    <!-- Carousel Section - Upcoming Auctions -->
    @if($upcomingAuctions->isNotEmpty())
        <div class="relative bg-gray-900 overflow-hidden">
            <div id="carousel" class="relative" x-data="{ currentSlide: 0, totalSlides: {{ $upcomingAuctions->count() }} }">
                <!-- Carousel Items -->
                <div class="relative h-[600px]">
                    @foreach($upcomingAuctions as $index => $auction)
                        <div x-show="currentSlide === {{ $index }}"
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 transform translate-x-full"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             x-transition:leave="transition ease-in duration-500"
                             x-transition:leave-start="opacity-100 transform translate-x-0"
                             x-transition:leave-end="opacity-0 transform -translate-x-full"
                             class="absolute inset-0"
                             style="display: none;">
                            
                            <!-- Background - Most Valuable Item -->
                            @if($auction->topItems->isNotEmpty() && $auction->topItems->first()->primaryImage)
                                <img src="{{ asset('storage/' . $auction->topItems->first()->primaryImage->path) }}" 
                                     alt="{{ $auction->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-r from-primary-700 to-primary-900"></div>
                            @endif
                            
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-transparent"></div>
                            
                            <!-- Content -->
                            <div class="absolute inset-0 flex items-center">
                                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                                        <!-- Left: Auction Details -->
                                        <div>
                                            <span class="inline-block px-4 py-1 bg-red-600 text-white text-sm font-semibold rounded-full mb-4">
                                                Upcoming Auction
                                            </span>
                                            <h2 class="text-5xl font-bold text-white mb-4">{{ $auction->title }}</h2>
                                            @if($auction->catalogue)
                                                <p class="text-2xl text-purple-200 mb-4">{{ $auction->catalogue->name }}</p>
                                            @endif
                                            
                                            <div class="flex items-center text-white mb-6 space-x-6">
                                                <div class="flex items-center">
                                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="text-lg">{{ $auction->auction_date->format('F j, Y') }}</span>
                                                </div>
                                                @if($auction->location)
                                                    <div class="flex items-center">
                                                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        <span class="text-lg">{{ $auction->location->name }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            @if($auction->catalogue)
                                                <p class="text-white text-lg mb-8">
                                                    Featuring {{ $auction->catalogue->items->count() }} exceptional items
                                                </p>
                                            @endif

                                            <a href="{{ route('auctions.show', $auction) }}" 
                                               class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-8 rounded-lg transition text-lg">
                                                View Auction Details →
                                            </a>
                                        </div>
                                        <!-- Right: Top 4 Most Valuable Items Thumbnails -->
                                        @if($auction->topItems->isNotEmpty())
                                            <div class="hidden lg:grid grid-cols-2 gap-4">
                                                @foreach($auction->topItems as $topItem)
                                                    <a href="{{ route('items.show', $topItem) }}" 
                                                       class="bg-white/10 backdrop-blur-sm rounded-lg overflow-hidden hover:bg-white/20 transition group">
                                                        @if($topItem->primaryImage)
                                                            <img src="{{ asset('storage/' . $topItem->primaryImage->path) }}" 
                                                                 alt="{{ $topItem->title }}"
                                                                 class="w-full aspect-square object-cover group-hover:scale-105 transition-transform duration-300">
                                                        @else
                                                            <div class="w-full aspect-square bg-gray-700 flex items-center justify-center">
                                                                <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
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
                    @foreach($upcomingAuctions as $index => $auction)
                        <button @click="currentSlide = {{ $index }}"
                                :class="currentSlide === {{ $index }} ? 'bg-white' : 'bg-white/50'"
                                class="w-3 h-3 rounded-full transition"></button>
                    @endforeach
                </div>

                <!-- Auto-advance carousel -->
                <div x-init="setInterval(() => { currentSlide = currentSlide === totalSlides - 1 ? 0 : currentSlide + 1 }, 5000)"></div>
            </div>
        </div>
    @endif

    <div class="bg-gray-50">
        <!-- Featured Items from Upcoming Auctions -->
        @if($featuredItems->isNotEmpty())
            <section class="py-16 bg-white pb-4 mb-3">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-4xl font-bold text-gray-900 mb-4">Featured Items</h2>
                        <p class="text-xl text-gray-600">Exceptional pieces from our upcoming auctions</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($featuredItems as $item)
                            <a href="{{ route('items.show', $item) }}" class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow overflow-hidden group">
                                <div class="relative aspect-square overflow-hidden bg-gray-200">
                                    @if($item->primaryImage)
                                        <img src="{{ asset('storage/' . $item->primaryImage->path) }}" 
                                             alt="{{ $item->title }}" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $item->title }}</h3>
                                    @if($item->estimated_price)
                                        <p class="text-lg font-bold text-primary-700 mb-2">£{{ number_format($item->estimated_price, 2) }}</p>
                                    @endif
                                    @if(isset($item->featured_auction))
                                        <p class="text-xs text-gray-500">
                                            Auction: {{ $item->featured_auction->auction_date->format('M d, Y') }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 text-center mt-10">
                        <a href="{{ route('items.browse') }}" class="inline-block w-full sm:w-auto bg-primary-700 hover:bg-primary-800 text-white font-semibold py-3 px-8 rounded-lg transition text-center">
                            Browse All Items →
                        </a>
                    </div>
                </div>
            </section>
        @endif

        <!-- About Fotherby's Section -->
        <section class="bg-gradient-to-r from-primary-700 to-primary-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="text-white">
                        <h2 class="text-4xl font-bold mb-6">Welcome to Fotherby's</h2>
                        <p class="text-lg text-purple-100 mb-6 leading-relaxed">
                            With over a century of expertise in fine art and antiques, Fotherby's has established itself as one of the premier auction houses in the United Kingdom. Our commitment to excellence, integrity, and client satisfaction has made us the trusted choice for collectors, dealers, and institutions worldwide.
                        </p>
                        <p class="text-lg text-purple-100 mb-8 leading-relaxed">
                            From rare antiquities to contemporary art, jewelry to fine furniture, we handle each piece with the care and expertise it deserves. Our team of specialists brings unparalleled knowledge to every auction, ensuring fair valuations and exceptional results.
                        </p>
                        <div class="flex gap-4">
                            <a href="{{ route('about') }}" class="inline-block bg-white text-primary-700 hover:bg-gray-100 font-semibold py-3 px-6 rounded-lg transition">
                                About Us →
                            </a>
                            <a href="{{ route('services') }}" class="inline-block border-2 border-white text-white hover:bg-white/10 font-semibold py-3 px-6 rounded-lg transition">
                                Our Services
                            </a>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 text-white">
                            <div class="text-4xl font-bold mb-2">65</div>
                            <p class="text-purple-100">Years of Excellence</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 text-white">
                            <div class="text-4xl font-bold mb-2">700K+</div>
                            <p class="text-purple-100">Items Sold per Year</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 text-white">
                            <div class="text-4xl font-bold mb-2">200+</div>
                            <p class="text-purple-100">Auctions Yearly</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Upcoming Auctions Section -->
        @if($upcomingAuctions->isNotEmpty())
            <section class="py-16 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
                    <div class="text-center mb-12">
                        <h2 class="text-4xl font-bold text-gray-900 mb-4">Upcoming Auctions</h2>
                        <p class="text-xl text-gray-600">Mark your calendar for these exciting events</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-30">
                        @foreach($upcomingAuctions->take(6) as $auction)
                            <a href="{{ route('auctions.show', $auction) }}" class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-all overflow-hidden border border-gray-200 group">
                                <div class="bg-gradient-to-r from-primary-700 to-primary-600 p-6 text-white">
                                    <h3 class="font-bold text-xl mb-2">{{ $auction->title }}</h3>
                                    @if($auction->catalogue)
                                        <p class="text-purple-100">{{ $auction->catalogue->name }}</p>
                                    @endif
                                </div>
                                <div class="p-6">
                                    <div class="flex items-center text-gray-700 mb-3">
                                        <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="font-semibold">{{ $auction->auction_date->format('F j, Y') }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-600 mb-4">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>{{ $auction->start_time }}</span>
                                    </div>
                                    @if($auction->location)
                                        <div class="flex items-center text-gray-600 mb-4">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span>{{ $auction->location->name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 text-center mt-10">
                        <a href="{{ route('auctions.browse') }}" class="inline-block w-full sm:w-auto bg-primary-700 hover:bg-primary-800 text-white font-semibold py-3 px-8 rounded-lg transition text-center">
                            View All Auctions →
                        </a>
                    </div>
                </div>
            </section>
        @endif

        <!-- Highlighted Items -->
        @if($highlightedItems->isNotEmpty())
            <section class="py-16 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-4xl font-bold text-gray-900 mb-4">Highlighted Items</h2>
                        <p class="text-xl text-gray-600">Premium pieces available in upcoming auctions</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($highlightedItems as $item)
                            <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow overflow-hidden">
                                <a href="{{ route('items.show', $item) }}" class="block">
                                    <div class="relative aspect-square overflow-hidden bg-gray-200">
                                        @if($item->primaryImage)
                                            <img src="{{ asset('storage/' . $item->primaryImage->path) }}" 
                                                 alt="{{ $item->title }}" 
                                                 class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute top-3 right-3 bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                            PREMIUM
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2">{{ $item->title }}</h3>
                                        @if($item->category)
                                            <p class="text-sm text-gray-500 mb-3">{{ $item->category->name }}</p>
                                        @endif
                                        @if($item->estimated_price)
                                            <p class="text-2xl font-bold text-primary-700 mb-3">£{{ number_format($item->estimated_price, 2) }}</p>
                                        @endif
                                        @if($item->next_auction)
                                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                                                <p class="text-xs text-gray-600 mb-1">Available in:</p>
                                                <p class="font-semibold text-sm text-primary-700">{{ $item->next_auction->title }}</p>
                                                <p class="text-xs text-gray-600">{{ $item->next_auction->auction_date->format('M d, Y') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- Our Locations -->
        @if($locations->isNotEmpty())
            <section class="py-16 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Locations</h2>
                        <p class="text-xl text-gray-600">Visit our prestigious auction venues</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($locations as $location)
                            <a href="{{ route('locations.index', ['location' => $location->id]) }}" class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow overflow-hidden group">
                                @if($location->image_path)
                                    <div class="relative h-64 overflow-hidden">
                                        <img src="{{ asset('storage/' . $location->image_path) }}" 
                                             alt="{{ $location->name }}" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                        <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                            <h3 class="font-bold text-xl mb-1">{{ $location->name }}</h3>
                                        </div>
                                    </div>
                                @endif
                                <div class="p-6">
                                    @if($location->address)
                                        <div class="flex items-start text-gray-600">
                                            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <p class="text-sm">{{ $location->address }}</p>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 text-center mt-10">
                        <a href="{{ route('locations.index') }}" class="inline-block w-full sm:w-auto bg-primary-700 hover:bg-primary-800 text-white font-semibold py-3 px-8 rounded-lg transition text-center">
                            View All Locations →
                        </a>
                    </div>
                </div>
            </section>
        @endif

        <!-- Quick Links Section -->
        <section class="py-16 bg-gradient-to-r from-primary-900 to-primary-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Contact Us -->
                    <a href="{{ route('contact') }}" class="group">
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-8 hover:bg-white/20 transition text-center">
                            <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Contact Us</h3>
                            <p class="text-gray-300 text-sm">Get in touch with our team</p>
                        </div>
                    </a>

                    <!-- How to Bid -->
                    <a href="{{ route('how-to-bid') }}" class="group">
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-8 hover:bg-white/20 transition text-center">
                            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2">How to Bid</h3>
                            <p class="text-gray-300 text-sm">Learn about our bidding process</p>
                        </div>
                    </a>

                    <!-- Sell With Us -->
                    <a href="{{ route('sell-with-us') }}" class="group">
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-8 hover:bg-white/20 transition text-center">
                            <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Sell With Us</h3>
                            <p class="text-gray-300 text-sm">Consign your items to auction</p>
                        </div>
                    </a>

                    <!-- Valuations -->
                    <a href="{{ route('valuation') }}" class="group">
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-8 hover:bg-white/20 transition text-center">
                            <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Valuations</h3>
                            <p class="text-gray-300 text-sm">Get expert valuations</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>

    </div>
</x-layouts.app>
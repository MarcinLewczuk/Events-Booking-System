<x-layouts.app>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold mb-2 text-gray-900">Welcome back, {{ auth()->user()->first_name }}!</h2>
                            <p class="text-gray-600">Explore our approved items, catalogues, and upcoming auctions.</p>
                        </div>
                        <!-- My Inbox Button -->
                        <a href="{{ route('customer.inbox.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            My Inbox
                        </a>
                    </div>
                </div>
            </div>
            <!-- My Seat Bookings -->
            @if($seatBookings->isNotEmpty())
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900">My Seat Bookings</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($seatBookings as $booking)
                            <div class="bg-white rounded-lg shadow-sm border-l-4 border-purple-600 p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $booking->auction->title }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $booking->auction->location->name }}</p>
                                    </div>
                                    <div class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full">
                                        <span class="text-lg font-bold">{{ $booking->seat_number }}</span>
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm text-gray-600 mb-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $booking->auction->auction_date->format('M d, Y') }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $booking->auction->start_time }}
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('auctions.show', $booking->auction) }}" 
                                       class="flex-1 text-center bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold py-2 px-3 rounded-lg transition duration-200">
                                        View Auction
                                    </a>
                                    <a href="{{ route('customer.seat-booking.show', $booking->auction) }}" 
                                       class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold py-2 px-3 rounded-lg transition duration-200">
                                        View Seat Map
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <!-- Recommended Auctions -->
            @if($userTags->isNotEmpty() && $recommendedAuctions->isNotEmpty())
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Recommended Auctions for You</h3>
                        <a href="{{ route('auctions.browse') }}" class="text-sm text-purple-600 hover:text-purple-800 font-semibold">
                            View All →
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($recommendedAuctions as $auction)
                            <a href="{{ route('auctions.show', $auction) }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                                <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-4 text-white relative">
                                    <div class="absolute top-2 right-2 bg-white text-purple-700 text-xs font-bold px-2 py-1 rounded-full">
                                        {{ $auction->match_percentage }}% Match
                                    </div>
                                    <h4 class="font-bold text-lg mb-1">{{ $auction->title }}</h4>
                                    @if($auction->catalogue)
                                        <p class="text-sm text-purple-100">{{ $auction->catalogue->name }}</p>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $auction->auction_date->format('M d, Y') }}
                                            @if($auction->start_time)
                                                at {{ $auction->start_time }}
                                            @endif
                                        </div>
                                        @if($auction->location)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ $auction->location->name }}
                                            </div>
                                        @endif
                                        <div class="flex items-center text-purple-600 font-semibold">
                                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                            </svg>
                                            {{ $auction->matching_items_count }} matching items
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Recommended Items -->
            @if($userTags->isNotEmpty() && $recommendedItems->isNotEmpty())
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Items You Might Like</h3>
                        <a href="{{ route('items.browse') }}" class="text-sm text-purple-600 hover:text-purple-800 font-semibold">
                            View All →
                        </a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($recommendedItems as $item)
                            <a href="{{ route('items.show', $item) }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden group relative">
                                <div class="absolute top-2 right-2 z-10 bg-purple-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                    {{ $item->match_percentage }}% Match
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
                                        <p class="text-lg font-bold text-purple-600">£{{ number_format($item->estimated_price, 2) }}</p>
                                    @endif
                                    <div class="mt-2 flex items-center text-xs text-purple-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        {{ $item->matching_tags_count }} matching {{ Str::plural('tag', $item->matching_tags_count) }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Browse Items -->
                <a href="{{ route('items.browse') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Browse Items</h3>
                                <p class="text-sm text-gray-600">View approved items</p>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Browse Auctions -->
                <a href="{{ route('auctions.browse') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Upcoming Auctions</h3>
                                <p class="text-sm text-gray-600">View scheduled events</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Preferences -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Your Preferences</h3>
                        <a href="{{ route('profile.tags') }}" class="text-sm text-purple-600 hover:text-purple-800 font-semibold">
                            Manage Tags →
                        </a>
                    </div>
                    <p class="text-sm text-gray-600">
                        @if(auth()->user()->interestedTags->count() > 0)
                            You're following {{ auth()->user()->interestedTags->count() }} tag(s). We'll recommend items, catalogues, and auctions based on your interests.
                        @else
                            You haven't selected any interests yet. Add tags to get personalized recommendations!
                        @endif
                    </p>
                    @if(auth()->user()->interestedTags->count() > 0)
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach(auth()->user()->interestedTags->take(10) as $tag)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-700">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                            @if(auth()->user()->interestedTags->count() > 10)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                                    +{{ auth()->user()->interestedTags->count() - 10 }} more
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
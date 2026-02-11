<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Auction Approvals</h1>
                        <p class="mt-1 text-sm text-gray-600">Review and approve auctions awaiting approval</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-4 py-2 bg-red-100 text-red-800 rounded-lg font-semibold">
                            {{ $auctions->total() }} Pending
                        </span>
                    </div>
                </div>
            </div>

            <!-- Search & Filters -->
            <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form method="GET" action="{{ route('admin.approvals.auctions') }}" x-data="{ showFilters: {{ request()->hasAny(['location_id', 'created_by', 'date_from', 'date_to', 'sort_by']) ? 'true' : 'false' }} }">
                    <!-- Search Bar -->
                    <div class="flex gap-3 mb-4">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search by title, description..."
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent">
                        <button type="button" @click="showFilters = !showFilters" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filters
                        </button>
                        <x-buttons.primary type="submit" class="bg-red-600 hover:bg-red-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search
                        </x-buttons.primary>
                        @if(request()->hasAny(['search', 'location_id', 'created_by', 'date_from', 'date_to']))
                            <a href="{{ route('admin.approvals.auctions') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                Clear
                            </a>
                        @endif
                    </div>

                    <!-- Advanced Filters -->
                    <div x-show="showFilters" x-collapse>
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 pt-4 border-t border-gray-200">
                            <!-- Location Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                <select name="location_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent">
                                    <option value="">All Locations</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Creator Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Created By</label>
                                <select name="created_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent">
                                    <option value="">All Creators</option>
                                    @foreach($creators as $creator)
                                        <option value="{{ $creator->id }}" {{ request('created_by') == $creator->id ? 'selected' : '' }}>
                                            {{ $creator->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date From -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent">
                            </div>

                            <!-- Date To -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent">
                            </div>

                            <!-- Sort By -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                                <select name="sort_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent">
                                    <option value="approval_status_changed_at" {{ request('sort_by') == 'approval_status_changed_at' ? 'selected' : '' }}>Submission Date</option>
                                    <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Title</option>
                                    <option value="auction_date" {{ request('sort_by') == 'auction_date' ? 'selected' : '' }}>Auction Date</option>
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @if($auctions->isEmpty())
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">All Caught Up!</h3>
                    <p class="text-gray-600">
                        @if(request()->hasAny(['search', 'location_id', 'created_by', 'date_from', 'date_to']))
                            No auctions match your filters.
                        @else
                            There are no auctions awaiting approval at this time.
                        @endif
                    </p>
                </div>
            @else
                <!-- Auctions List -->
                <div class="space-y-4">
                    @foreach($auctions as $auction)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $auction->title }}</h3>
                                        
                                        @if($auction->description)
                                            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($auction->description, 150) }}</p>
                                        @endif
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <div>
                                                    <span class="font-medium">Date:</span>
                                                    {{ $auction->auction_date ? $auction->auction_date->format('M d, Y') : 'Not set' }}
                                                    @if($auction->start_time)
                                                        at {{ \Carbon\Carbon::parse($auction->start_time)->format('g:i A') }}
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                <div>
                                                    <span class="font-medium">Location:</span>
                                                    {{ $auction->location?->name ?? 'Not set' }}
                                                </div>
                                            </div>

                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                <div>
                                                    <span class="font-medium">Created by:</span>
                                                    {{ $auction->creator?->full_name ?? 'Unknown' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center text-sm text-gray-600 mb-4">
                                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                            <div>
                                                <span class="font-medium">Catalogue:</span>
                                                {{ $auction->catalogue?->name ?? 'Not set' }}
                                            </div>
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            Submitted {{ $auction->approval_status_changed_at?->diffForHumans() }}
                                        </div>
                                    </div>

                                    <div class="ml-6">
                                        <x-buttons.primary href="{{ route('admin.approvals.auction', $auction) }}" class="bg-red-600 hover:bg-red-700">
                                            Review & Approve
                                        </x-buttons.primary>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $auctions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
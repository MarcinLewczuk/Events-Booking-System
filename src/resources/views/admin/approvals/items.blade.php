<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Item Approvals</h1>
                        <p class="mt-1 text-sm text-gray-600">Review and approve items awaiting approval</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-4 py-2 bg-orange-100 text-orange-800 rounded-lg font-semibold">
                            {{ $items->total() }} Pending
                        </span>
                    </div>
                </div>
            </div>

            <!-- Search & Filters -->
            <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form method="GET" action="{{ route('admin.approvals.items') }}" x-data="{ showFilters: {{ request()->hasAny(['category_id', 'location_id', 'created_by', 'min_price', 'max_price', 'sort_by']) ? 'true' : 'false' }} }">
                    <!-- Search Bar -->
                    <div class="flex gap-3 mb-4">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search by title, description, creator..."
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent">
                        <button type="button" @click="showFilters = !showFilters" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filters
                        </button>
                        <x-buttons.primary type="submit">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search
                        </x-buttons.primary>
                        @if(request()->hasAny(['search', 'category_id', 'location_id', 'created_by', 'min_price', 'max_price']))
                            <a href="{{ route('admin.approvals.items') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                Clear
                            </a>
                        @endif
                    </div>

                    <!-- Advanced Filters -->
                    <div x-show="showFilters" x-collapse>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-200">
                            <!-- Category Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Location Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                <select name="location_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent">
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
                                <select name="created_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent">
                                    <option value="">All Creators</option>
                                    @foreach($creators as $creator)
                                        <option value="{{ $creator->id }}" {{ request('created_by') == $creator->id ? 'selected' : '' }}>
                                            {{ $creator->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Min Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Min Price (£)</label>
                                <input type="number" name="min_price" value="{{ request('min_price') }}" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent">
                            </div>

                            <!-- Max Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Max Price (£)</label>
                                <input type="number" name="max_price" value="{{ request('max_price') }}" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent">
                            </div>

                            <!-- Sort By -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                                <select name="sort_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent">
                                    <option value="current_stage_entered_at" {{ request('sort_by') == 'current_stage_entered_at' ? 'selected' : '' }}>Submission Date</option>
                                    <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Title</option>
                                    <option value="estimated_price" {{ request('sort_by') == 'estimated_price' ? 'selected' : '' }}>Price</option>
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @if($items->isEmpty())
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">All Caught Up!</h3>
                    <p class="text-gray-600">
                        @if(request()->hasAny(['search', 'category_id', 'location_id', 'created_by', 'min_price', 'max_price']))
                            No items match your filters.
                        @else
                            There are no items awaiting approval at this time.
                        @endif
                    </p>
                </div>
            @else
                <!-- Items Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($items as $item)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                            <!-- Image -->
                            <div class="aspect-square bg-gray-100">
                                @if($item->primaryImage)
                                    <img src="{{ Storage::url($item->primaryImage->path) }}" 
                                         alt="{{ $item->title }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-semibold text-gray-900 line-clamp-2 flex-1">{{ $item->title }}</h3>
                                </div>
                                
                                @if($item->short_description)
                                    <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $item->short_description }}</p>
                                @endif

                                <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                    <span>{{ $item->category?->name ?? 'Uncategorized' }}</span>
                                    @if($item->estimated_price)
                                        <span class="font-semibold text-gray-700">£{{ number_format($item->estimated_price, 2) }}</span>
                                    @endif
                                </div>

                                @if($item->creator)
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="font-medium">Artist:</span> {{ $item->creator }}
                                    </p>
                                @endif

                                <div class="text-xs text-gray-500 mb-4 space-y-1">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $item->current_stage_entered_at?->diffForHumans() }}
                                    </div>
                                </div>

                                <x-buttons.primary href="{{ route('admin.approvals.item', $item) }}" class="w-full justify-center">
                                    Review & Approve
                                </x-buttons.primary>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
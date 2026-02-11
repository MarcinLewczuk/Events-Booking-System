
<x-layouts.app>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Browse Items</h1>
                <p class="mt-2 text-gray-600">Explore our collection of approved auction items</p>
            </div>

            <!-- Recommended Items for Logged In Users -->
            @if($recommendedItems->isNotEmpty())
                <div class="mb-8 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-6 border-l-4 border-primary-700">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 text-primary-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-900">Recommended For You</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($recommendedItems as $recItem)
                            <a href="{{ route('items.show', $recItem) }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                                @if($recItem->primaryImage)
                                    <img src="{{ asset('storage/' . $recItem->primaryImage->path) }}" alt="{{ $recItem->title }}" class="w-full h-32 object-cover">
                                @else
                                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="p-3">
                                    <h3 class="font-semibold text-sm text-gray-900 truncate">{{ $recItem->title }}</h3>
                                    @if($recItem->estimated_price)
                                        <p class="text-sm text-primary-700 font-medium mt-1">£{{ number_format($recItem->estimated_price, 2) }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
                    
                    <!-- Toolbar -->
                    <div class="bg-white rounded-lg shadow-sm p-4 mb-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-600">
                            Showing <span class="font-semibold">{{ $items->firstItem() ?? 0 }}</span> to 
                            <span class="font-semibold">{{ $items->lastItem() ?? 0 }}</span> of 
                            <span class="font-semibold">{{ $items->total() }}</span> items
                        </div>

                        <div class="flex items-center gap-4">
                            <!-- Sort By -->
                            <form method="GET" action="{{ route('items.browse') }}" class="flex items-center gap-2">
                                <!-- Preserve filters -->
                                @if($search)<input type="hidden" name="search" value="{{ $search }}">@endif
                                @if($categoryId)<input type="hidden" name="category_id" value="{{ $categoryId }}">@endif
                                @if($locationId)<input type="hidden" name="location_id" value="{{ $locationId }}">@endif
                                @if($bandId)<input type="hidden" name="band_id" value="{{ $bandId }}">@endif
                                @foreach($tagIds as $tagId)<input type="hidden" name="tags[]" value="{{ $tagId }}">@endforeach
                                @if($minPrice)<input type="hidden" name="min_price" value="{{ $minPrice }}">@endif
                                @if($maxPrice)<input type="hidden" name="max_price" value="{{ $maxPrice }}">@endif
                                @if(request('year_from'))<input type="hidden" name="year_from" value="{{ request('year_from') }}">@endif
                                @if(request('year_to'))<input type="hidden" name="year_to" value="{{ request('year_to') }}">@endif
                                @if(request('weight_from'))<input type="hidden" name="weight_from" value="{{ request('weight_from') }}">@endif
                                @if(request('weight_to'))<input type="hidden" name="weight_to" value="{{ request('weight_to') }}">@endif
                                <input type="hidden" name="view" value="{{ $view }}">
                                
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

                            <!-- View Toggle -->
                            <div class="flex gap-1 border border-gray-300 rounded-md p-1">
                                <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" 
                                   class="p-2 rounded {{ $view == 'grid' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" 
                                   class="p-2 rounded {{ $view == 'list' ? 'bg-primary-700 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                
                <!-- Filters Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>
                        
                        <form method="GET" action="{{ route('items.browse') }}" id="filterForm">
                            <!-- Preserve view mode -->
                            <input type="hidden" name="view" value="{{ $view }}">
                            <!-- Action Buttons -->
                            <div class="space-y-2">
                                <button type="submit" class="w-full bg-primary-700 text-white px-4 py-2 rounded-md hover:bg-primary-800 transition">
                                    Apply Filters
                                </button>
                                <a href="{{ route('items.browse') }}" class="block w-full text-center bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition">
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
                            <div class="mb-4">
                                <label for="creator" class="block text-sm font-medium text-gray-700 mb-2">
                                    Filter by Creator/Artist
                                </label>
                                <input type="text" 
                                    name="creator" 
                                    id="creator"
                                    value="{{ request('creator') }}"
                                    placeholder="e.g., Van Gogh, Picasso..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <!-- Price Band -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price Band</label>
                                <select name="band_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <option value="">All Bands</option>
                                    @foreach($bands as $band)
                                        <option value="{{ $band->id }}" {{ $bandId == $band->id ? 'selected' : '' }}>
                                            {{ $band->name }} (£{{ number_format($band->min_price) }} - £{{ number_format($band->max_price) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="min_price" value="{{ $minPrice }}" placeholder="Min" 
                                           class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <input type="number" name="max_price" value="{{ $maxPrice }}" placeholder="Max" 
                                           class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                            </div>

                            <!-- Year of Creation Range -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Year of Creation</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="year_from" value="{{ request('year_from') }}" placeholder="From" min="1" max="{{ date('Y') }}" 
                                           class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <input type="number" name="year_to" value="{{ request('year_to') }}" placeholder="To" min="1" max="{{ date('Y') }}" 
                                           class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Filter by the year the item was created</p>
                            </div>

                            <!-- Weight Range -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="weight_from" value="{{ request('weight_from') }}" placeholder="Min" step="0.01" min="0" 
                                           class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <input type="number" name="weight_to" value="{{ request('weight_to') }}" placeholder="Max" step="0.01" min="0" 
                                           class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Filter by weight in kilograms</p>
                            </div>

                            <!-- Tags -->
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Tags
                                        @auth
                                            @if($userTags->isNotEmpty())
                                                <span class="text-xs text-primary-600">(Your interests)</span>
                                            @endif
                                        @endauth
                                    </label>

                                    <div class="flex gap-2">
                                        @auth
                                            @if($userTags->isNotEmpty())
                                                <button
                                                    type="button"
                                                    onclick="restoreUserTags()"
                                                    class="text-xs text-primary-600 hover:text-primary-700 hover:underline"
                                                >
                                                    Restore my interests
                                                </button>
                                            @endif
                                        @endauth

                                        <button
                                            type="button"
                                            onclick="clearAllTags()"
                                            class="text-xs text-red-600 hover:text-red-700 hover:underline"
                                        >
                                            Clear all
                                        </button>
                                    </div>
                                </div>

                                <!-- Flag to prevent auto-select on refresh -->
                                <input type="hidden" name="clear_tags" id="clear_tags" value="0">

                                <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-200 rounded p-2">
                                    @foreach($tags as $tag)
                                        @php
                                            $isUserTag = $userTags->contains($tag->id);

                                            $isChecked =
                                                in_array($tag->id, $tagIds)
                                                || (
                                                    !request()->boolean('clear_tags')
                                                    && empty($tagIds)
                                                    && $isUserTag
                                                );
                                        @endphp

                                        <label class="flex items-center {{ $isUserTag ? 'bg-purple-50 p-1 rounded' : '' }}">
                                            <input
                                                type="checkbox"
                                                name="tags[]"
                                                value="{{ $tag->id }}"
                                                {{ $isChecked ? 'checked' : '' }}
                                                class="tag-checkbox rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                                data-user-tag="{{ $isUserTag ? '1' : '0' }}"
                                            >

                                            <span class="ml-2 text-sm {{ $isUserTag ? 'font-semibold text-primary-700' : 'text-gray-700' }}">
                                                {{ $tag->name }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <script>
                                function clearAllTags() {
                                    document.querySelectorAll('.tag-checkbox').forEach(cb => cb.checked = false);
                                    document.getElementById('clear_tags').value = 1;
                                    document.getElementById('filterForm').submit();
                                }

                                function restoreUserTags() {
                                    document.querySelectorAll('.tag-checkbox').forEach(cb => {
                                        cb.checked = cb.dataset.userTag === '1';
                                    });
                                    document.getElementById('clear_tags').value = 0;
                                }
                            </script>
                        </form>
                    </div>
                </div>

                <!-- Items Display -->
                <div class="lg:col-span-3">


                    <!-- Items Grid/List -->
                    @if($items->isEmpty())
                        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No items found</h3>
                            <p class="text-gray-600 mb-4">Try adjusting your filters or search terms</p>
                            <a href="{{ route('items.browse') }}" class="inline-block bg-primary-700 text-white px-6 py-2 rounded-md hover:bg-primary-800 transition">
                                Clear Filters
                            </a>
                        </div>
                    @else
                        @if($view == 'grid')
                            <!-- Grid View -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($items as $item)
                                    <a href="{{ route('items.show', $item) }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                                        <!-- Image -->
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
                                            @auth
                                                @if(isset($item->recommendation_score) && $item->recommendation_score > 0)
                                                    <div class="absolute top-2 right-2 bg-purple-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                                        {{ round($item->recommendation_score) }}% Match
                                                    </div>
                                                @endif
                                            @endauth
                                        </div>
                                        
                                        <!-- Content -->
                                        <div class="p-4">
                                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $item->title }}</h3>
                                            
                                            @if($item->category)
                                                <p class="text-xs text-gray-500 mb-2">{{ $item->category->name }}</p>
                                            @endif
                                            
                                            @if($item->short_description)
                                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $item->short_description }}</p>
                                            @endif
                                            
                                            <div class="flex items-center justify-between">
                                                @if($item->estimated_price)
                                                    <div>
                                                        <span class="text-xs text-gray-500">Est. Price</span>
                                                        <p class="text-lg font-bold text-primary-700">£{{ number_format($item->estimated_price, 2) }}</p>
                                                    </div>
                                                @endif
                                                
                                                @if($item->location)
                                                    <span class="text-xs text-gray-500 flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        {{ $item->location->name }}
                                                    </span>
                                                @endif
                                            </div>

                                            @if($item->tags->isNotEmpty())
                                                <div class="mt-3 flex flex-wrap gap-1">
                                                    @foreach($item->tags->take(3) as $tag)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $userTags->contains($tag->id) ? 'bg-purple-100 text-primary-700' : 'bg-gray-100 text-gray-700' }}">
                                                            {{ $tag->name }}
                                                        </span>
                                                    @endforeach
                                                    @if($item->tags->count() > 3)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                                            +{{ $item->tags->count() - 3 }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <!-- List View -->
                            <div class="space-y-4">
                                @foreach($items as $item)
                                    <a href="{{ route('items.show', $item) }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden flex group">
                                        <!-- Thumbnail -->
                                        <div class="w-32 h-32 flex-shrink-0 bg-gray-200 relative overflow-hidden">
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
                                        
                                        <!-- Content -->
                                        <div class="flex-1 p-4 flex flex-col justify-between">
                                            <div>
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <h3 class="font-semibold text-gray-900 mb-1">{{ $item->title }}</h3>
                                                        @if($item->category)
                                                            <p class="text-xs text-gray-500 mb-2">{{ $item->category->name }}</p>
                                                        @endif
                                                    </div>
                                                    @auth
                                                        @if(isset($item->recommendation_score) && $item->recommendation_score > 0)
                                                            <div class="ml-2 bg-purple-600 text-white text-xs font-bold px-2 py-1 rounded-full whitespace-nowrap">
                                                                {{ round($item->recommendation_score) }}% Match
                                                            </div>
                                                        @endif
                                                    @endauth
                                                </div>
                                                
                                                @if($item->short_description)
                                                    <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ $item->short_description }}</p>
                                                @endif
                                            </div>
                                            
                                            <div class="flex items-center justify-between mt-2">
                                                <div class="flex items-center gap-4">
                                                    @if($item->estimated_price)
                                                        <div>
                                                            <span class="text-xs text-gray-500">Est. Price</span>
                                                            <p class="text-sm font-bold text-primary-700">£{{ number_format($item->estimated_price, 2) }}</p>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($item->location)
                                                        <div class="flex items-center text-xs text-gray-500">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            </svg>
                                                            {{ $item->location->name }}
                                                        </div>
                                                    @endif

                                                    @if($item->band)
                                                        <div class="text-xs text-gray-500">
                                                            Band: {{ $item->band->name }}
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                @if($item->tags->isNotEmpty())
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($item->tags->take(3) as $tag)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $userTags->contains($tag->id) ? 'bg-purple-100 text-primary-700' : 'bg-gray-100 text-gray-700' }}">
                                                                {{ $tag->name }}
                                                            </span>
                                                        @endforeach
                                                        @if($item->tags->count() > 3)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                                                +{{ $item->tags->count() - 3 }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $items->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
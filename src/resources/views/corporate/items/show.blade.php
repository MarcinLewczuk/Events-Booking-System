
<x-layouts.app>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb -->
            <nav class="mb-6 text-sm">
                <ol class="flex items-center space-x-2 text-gray-500">
                    <li><a href="{{ route('home') }}" class="hover:text-primary-700">Home</a></li>
                    <li>/</li>
                    <li><a href="{{ route('items.browse') }}" class="hover:text-primary-700">Items</a></li>
                    <li>/</li>
                    <li class="text-gray-900 font-medium">{{ $item->title }}</li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                
            <!-- Images Section -->
            <div 
                x-data="{
                    activeImage: '{{ $item->primaryImage 
                        ? asset('storage/' . $item->primaryImage->path) 
                        : '' }}'
                }"
            >
    <!-- Main Image -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4">
        <template x-if="activeImage">
            <img 
                :src="activeImage"
                alt="{{ $item->title }}"
                class="w-full aspect-square object-cover transition"
            >
        </template>

        @if(!$item->primaryImage)
            <div class="w-full aspect-square bg-gray-200 flex items-center justify-center">
                <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif
    </div>

    <!-- Thumbnails -->
    @if($item->images->count() > 1)
        <div class="grid grid-cols-4 gap-2">
            @foreach($item->images as $image)
                @php
                    $imageUrl = asset('storage/' . $image->path);
                @endphp

                <button
                    type="button"
                    @click="activeImage = '{{ $imageUrl }}'"
                    class="relative bg-white rounded overflow-hidden border-2 transition
                           hover:border-primary-600"
                    :class="activeImage === '{{ $imageUrl }}'
                        ? 'border-primary-700 ring-2 ring-primary-300'
                        : 'border-transparent'"
                >
                    <img 
                        src="{{ $imageUrl }}"
                        alt="Thumbnail"
                        class="w-full aspect-square object-cover"
                    >
                </button>
            @endforeach
        </div>
    @endif
</div>

                <!-- Details Section -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $item->title }}</h1>

                    <!-- Creator/Artist -->
                    @if($item->creator)
                        <div class="mb-4 flex items-center text-gray-700">
                            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-sm">
                                <span class="font-medium">Creator:</span> {{ $item->creator }}
                            </span>
                        </div>
                    @endif

                    @if($item->category)
                        <p class="text-sm text-gray-500 mb-4">
                            Category: <span class="font-medium text-gray-700">{{ $item->category->name }}</span>
                        </p>
                    @endif

                    <!-- Auction Information -->
                    @php
                        $catalogue = $item->catalogues->first();
                        $auction = $catalogue?->auctions->first();
                    @endphp
                    
                    @if($auction)
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Auction Information</h3>
                                    <p class="text-sm text-gray-700 font-medium">{{ $auction->title }}</p>
                                    @if($catalogue)
                                        <p class="text-xs text-gray-600 mt-1">Catalogue: {{ $catalogue->name }}</p>
                                    @endif
                                    @if($auction->start_date && $auction->end_date)
                                        <p class="text-xs text-gray-600 mt-1">
                                            {{ $auction->start_date->format('M d, Y') }} - {{ $auction->end_date->format('M d, Y') }}
                                        </p>
                                    @endif
                                    @if($auction->status)
                                        <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $auction->status === 'open' ? 'bg-green-100 text-green-800' : 
                                               ($auction->status === 'upcoming' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($auction->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @elseif($catalogue)
                        <div class="mb-6 p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Catalogue</h3>
                                    <p class="text-sm text-gray-700">{{ $catalogue->name }}</p>
                                    <p class="text-xs text-gray-600 mt-1">Not yet assigned to an auction</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border-l-4 border-gray-400">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-600">This item is not currently in any auction or catalogue.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Price -->
                    @if($item->estimated_price)
                        <div class="mb-6 p-4 bg-purple-50 rounded-lg border-l-4 border-primary-700">
                            <p class="text-sm text-gray-600 mb-1">Estimated Price</p>
                            <p class="text-3xl font-bold text-primary-700">£{{ number_format($item->estimated_price, 2) }}</p>
                            @if($item->band)
                                <p class="text-sm text-gray-600 mt-1">Band: {{ $item->band->name }}</p>
                            @endif
                        </div>
                    @endif

                    <!-- Description -->
                    @if($item->short_description)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                            <p class="text-gray-700">{{ $item->short_description }}</p>
                        </div>
                    @endif

                    @if($item->detailed_description)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Details</h3>
                            <p class="text-gray-700 whitespace-pre-line">{{ $item->detailed_description }}</p>
                        </div>
                    @endif

                    <!-- Additional Info -->
                    <div class="space-y-3 mb-6">
                        @if($item->dimensions)
                            <div class="flex items-start">
                                <span class="text-sm font-medium text-gray-600 w-32">Dimensions:</span>
                                <span class="text-sm text-gray-900">{{ $item->dimensions }}</span>
                            </div>
                        @endif

                        @if($item->year_of_creation)
                            <div class="flex items-start">
                                <span class="text-sm font-medium text-gray-600 w-32">Year Created:</span>
                                <span class="text-sm text-gray-900">{{ $item->year_of_creation }}</span>
                            </div>
                        @endif

                        @if($item->weight)
                            <div class="flex items-start">
                                <span class="text-sm font-medium text-gray-600 w-32">Weight:</span>
                                <span class="text-sm text-gray-900">{{ number_format($item->weight, 2) }} kg</span>
                            </div>
                        @endif
                        
                        @if($item->location)
                            <div class="flex items-start">
                                <span class="text-sm font-medium text-gray-600 w-32">Location:</span>
                                <span class="text-sm text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $item->location->name }}
                                </span>
                            </div>
                        @endif

                        @if($item->reserve_price)
                            <div class="flex items-start">
                                <span class="text-sm font-medium text-gray-600 w-32">Reserve Price:</span>
                                <span class="text-sm text-gray-900">£{{ number_format($item->reserve_price, 2) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Tags -->
                    @if($item->tags->isNotEmpty())
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($item->tags as $tag)
                                    <a href="{{ route('items.browse', ['tags' => [$tag->id]]) }}" 
                                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-primary-700 hover:bg-purple-200 transition">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Call to Action -->
                    <div class="pt-6 border-t border-gray-200">
                        @guest
                            <p class="text-sm text-gray-600 mb-4">
                                <svg class="w-5 h-5 inline mr-1 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <a href="{{ route('login') }}" class="text-primary-700 font-medium hover:underline">Sign in</a> to track this item and receive personalized recommendations.
                            </p>
                        @endguest

                        <x-buttons.primary href="{{ route('items.browse') }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Browse
                        </x-buttons.primary>
                    </div>
                </div>
            </div>

            <!-- Similar Items -->
            @if($similarItems->isNotEmpty())
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Similar Items</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($similarItems as $similarItem)
                            <a href="{{ route('items.show', $similarItem) }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                                @if($similarItem->primaryImage)
                                    <img src="{{ asset('storage/' . $similarItem->primaryImage->path) }}" 
                                         alt="{{ $similarItem->title }}" 
                                         class="w-full aspect-square object-cover">
                                @else
                                    <div class="w-full aspect-square bg-gray-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $similarItem->title }}</h3>
                                    @if($similarItem->estimated_price)
                                        <p class="text-primary-700 font-bold">£{{ number_format($similarItem->estimated_price, 2) }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
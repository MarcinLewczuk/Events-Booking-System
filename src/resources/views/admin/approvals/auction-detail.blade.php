
<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center text-sm text-gray-600 mb-4">
                    <a href="{{ route('admin.approvals.auctions') }}" class="hover:text-red-600">← Back to Approvals</a>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Review Auction for Approval</h1>
                <p class="mt-1 text-sm text-gray-600">Review and edit auction details before approving</p>
            </div>

            <!-- Approval Status Banner -->
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="font-semibold text-red-900 mb-1">Awaiting Your Approval</h3>
                        <p class="text-sm text-red-800">
                            Submitted by {{ $auction->creator?->full_name ?? 'Unknown' }} 
                            {{ $auction->approval_status_changed_at?->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Approval Actions -->
            <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Approval Actions</h3>
                <div class="flex flex-col sm:flex-row gap-3">
                    <form method="POST" action="{{ route('admin.auctions.approve', $auction) }}" class="flex-1">
                        @csrf
                        <x-buttons.success type="submit" class="w-full justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Approve Auction
                        </x-buttons.success>
                    </form>
                    <form method="POST" action="{{ route('admin.auctions.reject', $auction) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to reject this auction?');">
                        @csrf
                        <textarea name="rejection_reason" placeholder="Reason for rejection (optional)" class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-2 text-sm" rows="2"></textarea>
                        <x-buttons.danger type="submit" class="w-full justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject Auction
                        </x-buttons.danger>
                    </form>
                </div>
            </div>

            <!-- Edit Form -->
            <form method="POST" action="{{ route('admin.auctions.update', $auction) }}">
                @csrf
                @method('PATCH')

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="border-l-4 border-red-600 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Auction Details</h3>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Title -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Auction Title <span class="text-red-600">*</span></label>
                                <input type="text" 
                                       name="title" 
                                       value="{{ old('title', $auction->title) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition"
                                       required>
                            </div>

                            <!-- Catalogue -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Catalogue <span class="text-red-600">*</span></label>
                                <select name="catalogue_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition" required>
                                    <option value="">Select Catalogue</option>
                                    @foreach($catalogues as $cat)
                                        <option value="{{ $cat->id }}" {{ old('catalogue_id', $auction->catalogue_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }} ({{ $cat->items_count ?? $cat->items->count() }} items)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Auction Date -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Auction Date</label>
                                    <input type="date" 
                                           name="auction_date" 
                                           value="{{ old('auction_date', $auction->auction_date?->format('Y-m-d')) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition">
                                </div>

                                <!-- Start Time -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                                    <input type="time" 
                                           name="start_time" 
                                           value="{{ old('start_time', $auction->start_time) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition">
                                </div>
                            </div>

                            <!-- Location -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                                <select name="location_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition">
                                    <option value="">Select Location</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}" {{ old('location_id', $auction->location_id) == $loc->id ? 'selected' : '' }}>
                                            {{ $loc->name }} (Max: {{ $loc->max_attendance }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Auction Block -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Auction Block</label>
                                <input type="text" 
                                       name="auction_block" 
                                       value="{{ old('auction_block', $auction->auction_block) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition"
                                       placeholder="Optional block designation">
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-600">*</span></label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition" required>
                                    <option value="scheduled" {{ old('status', $auction->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="open" {{ old('status', $auction->status) == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="closed" {{ old('status', $auction->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="settled" {{ old('status', $auction->status) == 'settled' ? 'selected' : '' }}>Settled</option>
                                </select>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 mt-6">
                            <x-buttons.primary type="submit" class="bg-red-600 hover:bg-red-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Changes
                            </x-buttons.primary>
                            <x-buttons.secondary href="{{ route('admin.approvals.auctions') }}">
                                Cancel
                            </x-buttons.secondary>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Catalogue Items Preview (Read-only) -->
            @if($auction->catalogue && $auction->catalogue->items->isNotEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="border-l-4 border-red-600 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Catalogue Items ({{ $auction->catalogue->items->count() }})
                        </h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($auction->catalogue->items->take(12) as $item)
                                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                    <!-- Image -->
                                    <div class="aspect-square bg-gray-100">
                                        @if($item->primaryImage)
                                            <img src="{{ Storage::url($item->primaryImage->path) }}" 
                                                 alt="{{ $item->title }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Info -->
                                    <div class="p-3">
                                        <h4 class="font-semibold text-sm text-gray-900 line-clamp-1">{{ $item->title }}</h4>
                                        <p class="text-xs text-gray-600 mt-1">{{ $item->category?->name ?? 'Uncategorized' }}</p>
                                        @if($item->estimated_price)
                                            <p class="text-sm font-semibold text-gray-900 mt-2">£{{ number_format($item->estimated_price, 2) }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($auction->catalogue->items->count() > 12)
                            <div class="mt-4 text-center text-sm text-gray-600">
                                Showing 12 of {{ $auction->catalogue->items->count() }} items
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
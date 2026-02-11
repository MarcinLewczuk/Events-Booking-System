<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Edit Auction</h1>
                <p class="mt-1 text-sm text-gray-600">Update auction details and settings</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="border-l-4 border-red-600 p-6">
                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-red-800 mb-1">There were errors with your submission</h3>
                                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Status Banner -->
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-600 to-red-700 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $auction->title }}</h3>
                                    <p class="text-sm text-gray-600">
                                        @if($auction->auction_date)
                                            {{ $auction->auction_date->format('d M Y') }}
                                            @if($auction->start_time)
                                                at {{ $auction->start_time }}
                                            @endif
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.auctions.update', $auction) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Auction Details Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Auction Details</h3>
                            
                            <!-- Title -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Auction Title <span class="text-red-600">*</span>
                                </label>
                                <input type="text" 
                                       name="title" 
                                       value="{{ old('title', $auction->title) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition @error('title') border-red-500 @enderror"
                                       required>
                            </div>

                            <!-- Catalogue -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Catalogue <span class="text-red-600">*</span>
                                </label>

                                <select name="catalogue_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition @error('catalogue_id') border-red-500 @enderror"
                                        required>

                                    @foreach($catalogues as $c)
                                        @php
                                            $isCurrent = $auction->catalogue_id === $c->id;
                                        @endphp

                                        <option value="{{ $c->id }}"
                                            {{ old('catalogue_id', $auction->catalogue_id) == $c->id ? 'selected' : '' }}
                                            {{ !$isCurrent && $c->auction ? 'disabled' : '' }}>
                                            
                                            {{ $c->name }}
                                            ({{ $c->items()->count() }} items)
                                            @if($isCurrent)
                                                — current
                                            @elseif($c->auction)
                                                — already assigned
                                            @endif
                                        </option>
                                    @endforeach

                                </select>

                                <p class="mt-2 text-sm text-gray-600">
                                    Only one auction can be linked to a catalogue.
                                    The current catalogue can be kept or changed to an unassigned one.
                                </p>
                            </div>

                        </div>

                        <!-- Schedule Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Schedule</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Auction Date -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Auction Date
                                    </label>
                                    <input type="date" 
                                           name="auction_date" 
                                           value="{{ old('auction_date', $auction->auction_date?->format('Y-m-d')) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition">
                                </div>

                                <!-- Start Time -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Start Time
                                    </label>
                                    <input type="time" 
                                           name="start_time" 
                                           value="{{ old('start_time', $auction->start_time) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition">
                                </div>
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Location & Logistics</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Location -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Location
                                    </label>
                                    <select name="location_id" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition">
                                        <option value="">Select location (optional)</option>
                                        @foreach($locations as $loc)
                                            <option value="{{ $loc->id }}" {{ old('location_id', $auction->location_id) == $loc->id ? 'selected' : '' }}>
                                                {{ $loc->name }}
                                                @if($loc->max_attendance)
                                                    (Max: {{ $loc->max_attendance }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Auction Block -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Auction Block
                                    </label>
                                    <input type="text" 
                                           name="auction_block" 
                                           value="{{ old('auction_block', $auction->auction_block) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition">
                                </div>
                            </div>
                        </div>
                        <!-- Status Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Status</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Auction Status -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Auction Status <span class="text-red-600">*</span>
                                    </label>
                                    <select name="status" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition"
                                            required>
                                        <option value="scheduled" {{ old('status', $auction->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="open" {{ old('status', $auction->status) == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="closed" {{ old('status', $auction->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                        <option value="settled" {{ old('status', $auction->status) == 'settled' ? 'selected' : '' }}>Settled</option>
                                    </select>
                                </div>

                                <!-- Approval Status -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Approval Status
                                    </label>
                                    <select name="approval_status" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition">
                                        <option value="draft" {{ old('approval_status', $auction->approval_status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="awaiting_approval" {{ old('approval_status', $auction->approval_status) == 'awaiting_approval' ? 'selected' : '' }}>Awaiting Approval</option>
                                        <option value="approved" {{ old('approval_status', $auction->approval_status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ old('approval_status', $auction->approval_status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    <p class="mt-2 text-sm text-gray-600">
                                        Current: <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $auction->approval_status)) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                            <x-buttons.primary type="submit">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Auction
                            </x-buttons.primary>
                            <x-buttons.secondary href="{{ route('admin.auctions.index') }}">
                                Cancel
                            </x-buttons.secondary>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Section -->
            <div class="mt-8 bg-white rounded-lg shadow-sm border border-red-200">
                <div class="border-l-4 border-red-600 p-6">
                    <h2 class="text-lg font-semibold text-red-900 mb-2">Danger Zone</h2>
                    <p class="text-sm text-gray-600 mb-4">
                        Permanently delete this auction. This action cannot be undone.
                    </p>
                    <form method="POST" 
                          action="{{ route('admin.auctions.destroy', $auction) }}"
                          onsubmit="return confirm('Are you sure you want to delete this auction? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <x-buttons.danger type="submit">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Auction
                        </x-buttons.danger>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
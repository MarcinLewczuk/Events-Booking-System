
<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center text-sm text-gray-600 mb-4">
                    <a href="{{ route('admin.approvals.items') }}" class="hover:text-[#370671]">← Back to Approvals</a>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Review Item for Approval</h1>
                <p class="mt-1 text-sm text-gray-600">Review and edit item details before approving</p>
            </div>

            <!-- Approval Status Banner -->
            <div class="mb-6 p-4 bg-orange-50 border-l-4 border-orange-500 rounded-r-lg">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="font-semibold text-orange-900 mb-1">Awaiting Your Approval</h3>
                        <p class="text-sm text-orange-800">
                            Submitted by {{ $item->creator?->full_name ?? 'Unknown' }} 
                            {{ $item->current_stage_entered_at?->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Approval Actions -->
            <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Approval Actions</h3>
                <div class="flex flex-col sm:flex-row gap-3">
                    <form method="POST" action="{{ route('admin.items.approve', $item) }}" class="flex-1">
                        @csrf
                        <x-buttons.success type="submit" class="w-full justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Approve Item
                        </x-buttons.success>
                    </form>
                    <form method="POST" action="{{ route('admin.items.reject', $item) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to reject this item?');">
                        @csrf
                        <x-buttons.danger type="submit" class="w-full justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject Item
                        </x-buttons.danger>
                    </form>
                </div>
            </div>

            <!-- Edit Form -->
            <form method="POST" action="{{ route('admin.items.update', $item) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="border-l-4 border-[#370671] p-6">
                        
                        <!-- Images Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Images</h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Primary Image -->
                                @if($item->primaryImage)
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Primary Image</label>
                                        <img src="{{ Storage::url($item->primaryImage->path) }}" 
                                             alt="{{ $item->title }}"
                                             class="w-64 h-64 object-cover rounded-lg border-2 border-[#370671] shadow-md">
                                    </div>
                                @endif

                                <!-- Additional Images -->
                                @if($item->images->where('id', '!=', $item->primary_image_id)->count() > 0)
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Images</label>
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                            @foreach($item->images as $img)
                                                @if($item->primary_image_id != $img->id)
                                                    <img src="{{ Storage::url($img->path) }}" 
                                                         alt="Additional image"
                                                         class="w-full h-32 object-cover rounded-lg border border-gray-300 shadow-sm">
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Basic Information</h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Customer -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Customer</label>
                                    <select name="customer_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id', $item->customer_id) == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Title -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Title <span class="text-red-600">*</span></label>
                                    <input type="text" 
                                           name="title" 
                                           value="{{ old('title', $item->title) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Creator/Artist</label>
                                    <input type="text" 
                                           name="creator" 
                                           value="{{ old('creator', $item->creator) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition"
                                           placeholder="e.g., Vincent van Gogh, Unknown">
                                    <p class="mt-1 text-xs text-gray-600">Name of the artist, sculptor, or creator of the item</p>
                                </div>
                                <!-- Dimensions -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Dimensions</label>
                                    <input type="text" 
                                           name="dimensions" 
                                           value="{{ old('dimensions', $item->dimensions) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition"
                                           placeholder="e.g., 30cm x 20cm x 15cm">
                                    <p class="mt-1 text-xs text-gray-600">Physical dimensions of the item</p>
                                </div>
                                <!-- Short Description -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Short Description</label>
                                    <input type="text" 
                                           name="short_description" 
                                           value="{{ old('short_description', $item->short_description) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition"
                                           maxlength="255">
                                </div>

                                <!-- Detailed Description -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Detailed Description</label>
                                    <textarea name="detailed_description" 
                                              rows="5"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">{{ old('detailed_description', $item->detailed_description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Classification -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Classification</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                                    <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Intake Tier</label>
                                    <select name="intake_tier" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                        <option value="general" {{ old('intake_tier', $item->intake_tier) == 'general' ? 'selected' : '' }}>General</option>
                                        <option value="featured" {{ old('intake_tier', $item->intake_tier) == 'featured' ? 'selected' : '' }}>Featured</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Pricing & Fees</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Estimated Price (£)</label>
                                    <input type="number" 
                                           name="estimated_price" 
                                           step="0.01"
                                           value="{{ old('estimated_price', $item->estimated_price) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Reserve Price (£)</label>
                                    <input type="number" 
                                           name="reserve_price" 
                                           step="0.01"
                                           value="{{ old('reserve_price', $item->reserve_price) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Withdrawal Fee (£)</label>
                                    <input type="number" 
                                           name="withdrawal_fee" 
                                           step="0.01"
                                           value="{{ old('withdrawal_fee', $item->withdrawal_fee) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                </div>

                                <div class="md:col-span-3">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Price Band</label>
                                    <select name="band_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                        <option value="">Select Band</option>
                                        @foreach($bands as $band)
                                            <option value="{{ $band->id }}" {{ old('band_id', $item->band_id) == $band->id ? 'selected' : '' }}>
                                                {{ $band->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Tags</h3>
                            
                            @if($tags->isEmpty())
                                <p class="text-gray-500 text-sm italic">No tags available.</p>
                            @else
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                    @foreach($tags as $tag)
                                        <label class="relative flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-purple-50 transition-colors
                                            {{ $item->tags->contains($tag->id) ? 'bg-purple-50 border-[#370671] ring-2 ring-[#370671]' : '' }}">
                                            <input type="checkbox" 
                                                   name="tags[]" 
                                                   value="{{ $tag->id }}"
                                                   {{ $item->tags->contains($tag->id) ? 'checked' : '' }}
                                                   class="w-4 h-4 text-[#370671] border-gray-300 rounded focus:ring-[#370671]">
                                            <span class="ml-2 text-sm font-medium text-gray-900">{{ $tag->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                            <x-buttons.primary type="submit">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Changes
                            </x-buttons.primary>
                            <x-buttons.secondary href="{{ route('admin.approvals.items') }}">
                                Cancel
                            </x-buttons.secondary>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
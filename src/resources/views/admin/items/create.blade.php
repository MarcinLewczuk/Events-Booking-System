<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Create New Item</h1>
                <p class="mt-1 text-sm text-gray-600">Add a new item to the auction system</p>
            </div>

            <!-- Form Card -->
            <form method="POST" action="{{ route('admin.items.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="border-l-4 border-[#370671] p-6">
                        <!-- Error Messages -->
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

                        <!-- Basic Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Basic Information</h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Customer -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Customer
                                    </label>
                                    <select name="customer_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Title -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Title <span class="text-red-600">*</span>
                                    </label>
                                    <input type="text" 
                                           name="title" 
                                           value="{{ old('title') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition @error('title') border-red-500 @enderror"
                                           required>
                                </div>

                                <!-- Creator/Artist -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Creator/Artist
                                    </label>
                                    <input type="text" 
                                           name="creator" 
                                           value="{{ old('creator') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition"
                                           placeholder="e.g., Vincent van Gogh, Unknown">
                                    <p class="mt-1 text-xs text-gray-600">Name of the artist, sculptor, or creator of the item</p>
                                </div>

                                <!-- Short Description -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Short Description
                                    </label>
                                    <input type="text" 
                                           name="short_description" 
                                           value="{{ old('short_description') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition"
                                           maxlength="255">
                                </div>

                                <!-- Detailed Description -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Detailed Description
                                    </label>
                                    <textarea name="detailed_description" 
                                              rows="5"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">{{ old('detailed_description') }}</textarea>
                                </div>

                                <!-- Dimensions -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Dimensions
                                    </label>
                                    <input type="text" 
                                           name="dimensions" 
                                           value="{{ old('dimensions') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition"
                                           placeholder="e.g., 30cm x 20cm x 15cm">
                                    <p class="mt-1 text-xs text-gray-600">Physical dimensions of the item</p>
                                </div>
                                <!-- Year of Creation -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Year of Creation
                                    </label>
                                    <input type="number" 
                                           name="year_of_creation" 
                                           value="{{ old('year_of_creation') }}"
                                           min="1"
                                           max="{{ date('Y') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition"
                                           placeholder="e.g., {{ date('Y') - 100 }}">
                                    <p class="mt-1 text-xs text-gray-600">The year the physical item was created</p>
                                </div>

                                <!-- Weight -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Weight (kg)
                                    </label>
                                    <input type="number" 
                                           name="weight" 
                                           value="{{ old('weight') }}"
                                           step="0.01"
                                           min="0"
                                           max="99999.99"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition"
                                           placeholder="e.g., 2.5">
                                    <p class="mt-1 text-xs text-gray-600">Weight in kilograms (optional, for items where weight is relevant)</p>
                                </div>

                            </div>
                        </div>

                        <!-- Classification -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Classification</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Category -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Category
                                    </label>
                                    <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Intake Tier -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Intake Tier
                                    </label>
                                    <select name="intake_tier" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                        <option value="general" {{ old('intake_tier') == 'general' ? 'selected' : '' }}>General</option>
                                        <option value="featured" {{ old('intake_tier') == 'featured' ? 'selected' : '' }}>Featured</option>
                                    </select>
                                </div>
                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Status
                                    </label>
                                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                        <option value="intake" {{ old('status', 'intake') == 'intake' ? 'selected' : '' }}>Intake</option>
                                        <option value="photos" {{ old('status') == 'photos' ? 'selected' : '' }}>Photos</option>
                                        <option value="description" {{ old('status') == 'description' ? 'selected' : '' }}>Description</option>
                                        <option value="catalogue_ready" {{ old('status') == 'catalogue_ready' ? 'selected' : '' }}>Catalogue Ready</option>
                                        <option value="awaiting_approval" {{ old('status') == 'awaiting_approval' ? 'selected' : '' }}>Awaiting Approval</option>
                                    </select>
                                    <p class="mt-1 text-xs text-gray-600">Current processing status (Published requires approval)</p>
                                </div>
                                <!-- Location -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Storage Location
                                    </label>
                                    <select name="location_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                        <option value="">Select Location</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-gray-600">Where the item is currently stored</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Pricing & Fees</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Estimated Price -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Estimated Price (£)
                                    </label>
                                    <input type="number" 
                                           name="estimated_price" 
                                           step="0.01"
                                           value="{{ old('estimated_price') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                </div>

                                <!-- Reserve Price -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Reserve Price (£)
                                    </label>
                                    <input type="number" 
                                           name="reserve_price" 
                                           step="0.01"
                                           value="{{ old('reserve_price') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                </div>

                                <!-- Withdrawal Fee -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Withdrawal Fee (£)
                                    </label>
                                    <input type="number" 
                                           name="withdrawal_fee" 
                                           step="0.01"
                                           value="{{ old('withdrawal_fee') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                </div>

                                <!-- Band -->
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Price Band
                                    </label>
                                    <select name="band_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                        <option value="">Select Band</option>
                                        @foreach($bands as $band)
                                            <option value="{{ $band->id }}" {{ old('band_id') == $band->id ? 'selected' : '' }}>
                                                {{ $band->name }} 
                                                (£{{ $band->min_price, 0 }} - 
                                                @if($band->max_price)
                                                    £{{ $band->max_price, 0 }}
                                                @else
                                                    No Limit
                                                @endif)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Images</h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Primary Image -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Primary Image
                                    </label>
                                    <input type="file" 
                                           name="primary_image"
                                           id="primary_image"
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                           onchange="previewPrimaryImage(event)"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                    <p class="mt-1 text-xs text-gray-600">Max 5MB, JPEG/PNG</p>
                                    
                                    <!-- Primary Image Preview -->
                                    <div id="primary_preview" class="mt-4 hidden">
                                        <img id="primary_preview_img" src="" alt="Primary image preview" class="w-48 h-48 object-cover rounded-lg border-2 border-[#370671] shadow-md">
                                    </div>
                                </div>

                                <!-- Additional Images -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Additional Images
                                    </label>
                                    <input type="file" 
                                           name="images[]"
                                           id="additional_images"
                                            accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                           multiple
                                           onchange="previewAdditionalImages(event)"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition">
                                    <p class="mt-1 text-xs text-gray-600">Max 5MB per image, JPEG/PNG</p>
                                    
                                    <!-- Additional Images Preview -->
                                    <div id="additional_preview" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4 hidden"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Tags</h3>
                            
                            @if($tags->isEmpty())
                                <p class="text-gray-500 text-sm italic">No tags available. Contact an admin to create tags.</p>
                            @else
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                    @foreach($tags as $tag)
                                        <label class="relative flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-primary-50 transition-colors">
                                            <input type="checkbox" 
                                                   name="tags[]" 
                                                   value="{{ $tag->id }}"
                                                   {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                                   class="w-4 h-4 text-[#370671] border-gray-300 rounded focus:ring-[#370671]">
                                            <span class="ml-2 text-sm font-medium text-gray-900">
                                                {{ $tag->name }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Options -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Options</h3>
                            
                            <div class="flex items-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <input type="checkbox" 
                                       name="priority_flag" 
                                       value="1"
                                       {{ old('priority_flag') ? 'checked' : '' }}
                                       class="w-4 h-4 text-[#370671] border-gray-300 rounded focus:ring-[#370671]">
                                <label class="ml-3 text-sm font-medium text-gray-900">
                                    Priority Flag
                                    <span class="block text-xs text-gray-600 font-normal">Mark this item as high priority</span>
                                </label>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                            <x-buttons.primary type="submit">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Create Item
                            </x-buttons.primary>
                            <x-buttons.secondary href="{{ route('admin.items.index') }}">
                                Cancel
                            </x-buttons.secondary>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Image Preview Scripts -->
    <script>
        function previewPrimaryImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('primary_preview').classList.remove('hidden');
                    document.getElementById('primary_preview_img').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        function previewAdditionalImages(event) {
            const files = event.target.files;
            const preview = document.getElementById('additional_preview');
            preview.innerHTML = '';
            
            if (files.length > 0) {
                preview.classList.remove('hidden');
                
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Additional image preview" 
                                 class="w-full h-32 object-cover rounded-lg border border-gray-300 shadow-sm">
                        `;
                        preview.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }
    </script>
</x-app-layout>
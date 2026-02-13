<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    @if(isset($event))
                        Edit Event: {{ $event->title }}
                    @else
                        Create New Event
                    @endif
                </h1>
                <p class="mt-1 text-sm text-gray-600">
                    @if(isset($event))
                        Update event details and manage settings
                    @else
                        Add a new event to your platform
                    @endif
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Error Alert -->
                @if($errors->any())
                    <div class="bg-red-50 border-b border-red-200 p-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-red-800 mb-2">Please fix the following errors:</h3>
                                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" 
                      action="@isset($event){{ route('admin.events.update', $event) }}@else{{ route('admin.events.store') }}@endisset" 
                      enctype="multipart/form-data"
                      class="divide-y divide-gray-200">
                    @csrf
                    @isset($event)
                        @method('PUT')
                    @endisset

                    <!-- Basic Information Section -->
                    <div class="p-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Basic Information
                        </h2>

                        <div class="space-y-6">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Event Title <span class="text-red-600">*</span>
                                </label>
                                <input type="text" 
                                       id="title"
                                       name="title" 
                                       value="{{ old('title', $event->title ?? '') }}"
                                       placeholder="e.g., Summer Festival 2025, Jazz Night"
                                       class="w-full px-4 py-2 border @error('title') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent transition"
                                       required>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-600">A clear, descriptive title for your event</p>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Description <span class="text-red-600">*</span>
                                </label>
                                <textarea id="description"
                                          name="description" 
                                          rows="5"
                                          placeholder="Describe your event in detail. What will happen? Who will attend? Why should people come?"
                                          class="w-full px-4 py-2 border @error('description') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent transition"
                                          required>{{ old('description', $event->description ?? '') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-600">Minimum 50 characters. This will be displayed to potential attendees.</p>
                            </div>

                            <!-- Itinerary (Optional) -->
                            <div>
                                <label for="itinerary" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Event Itinerary
                                </label>
                                <textarea id="itinerary"
                                          name="itinerary" 
                                          rows="4"
                                          placeholder="Optional: Provide a detailed schedule (e.g., 2:00 PM - Opening Remarks, 2:30 PM - Main Event, etc.)"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent transition">{{ old('itinerary', $event->itinerary ?? '') }}</textarea>
                                <p class="mt-2 text-sm text-gray-600">Detailed schedule or agenda (optional)</p>
                            </div>

                            <!-- Category Field -->
                            <div>
                                <label for="category_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Category <span class="text-red-600">*</span>
                                </label>
                                <select id="category_id"
                                        name="category_id" 
                                        class="w-full px-4 py-2 border @error('category_id') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent transition"
                                        required>
                                    <option value="">Select a category...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id', $event->category_id ?? null) == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Date & Time Section -->
                    <div class="p-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Date & Time
                        </h2>

                        <div class="space-y-6">
                            <!-- Start DateTime -->
                            <div>
                                <label for="start_datetime" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Start Date & Time <span class="text-red-600">*</span>
                                </label>
                                <input type="datetime-local" 
                                       id="start_datetime"
                                       name="start_datetime" 
                                       value="{{ old('start_datetime', isset($event) && $event->start_datetime ? $event->start_datetime->format('Y-m-d\TH:i') : '') }}"
                                       class="w-full px-4 py-2 border @error('start_datetime') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent transition"
                                       required>
                                @error('start_datetime')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-600">When will your event start?</p>
                            </div>

                            <!-- End DateTime -->
                            <div>
                                <label for="end_datetime" class="block text-sm font-semibold text-gray-900 mb-2">
                                    End Date & Time <span class="text-red-600">*</span>
                                </label>
                                <input type="datetime-local" 
                                       id="end_datetime"
                                       name="end_datetime" 
                                       value="{{ old('end_datetime', isset($event) && $event->end_datetime ? $event->end_datetime->format('Y-m-d\TH:i') : '') }}"
                                       class="w-full px-4 py-2 border @error('end_datetime') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent transition"
                                       required>
                                @error('end_datetime')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-600">When will your event end?</p>
                            </div>
                        </div>
                    </div>

                    <!-- Capacity & Pricing Section -->
                    <div class="p-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Capacity & Pricing
                        </h2>

                        <div class="space-y-6">
                            <!-- Capacity -->
                            <div>
                                <label for="capacity" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Maximum Capacity <span class="text-red-600">*</span>
                                </label>
                                <input type="number" 
                                       id="capacity"
                                       name="capacity" 
                                       value="{{ old('capacity', $event->capacity ?? '') }}"
                                       min="1"
                                       placeholder="e.g., 500"
                                       class="w-full px-4 py-2 border @error('capacity') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent transition"
                                       required>
                                @error('capacity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-600">Total number of attendees your event can accommodate</p>
                            </div>

                            <!-- Paid Event Toggle -->
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="hidden" name="is_paid" value="0">
                                    <input type="checkbox" 
                                           id="is_paid"
                                           name="is_paid" 
                                           value="1"
                                           {{ old('is_paid', $event->is_paid ?? false) ? 'checked' : '' }}
                                           class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-600">
                                    <span class="ml-3 font-medium text-gray-900">This is a Paid Event</span>
                                </label>
                                <p class="mt-2 text-sm text-gray-600 ml-7">Enable this if attendees need to pay to book tickets</p>
                            </div>

                            <!-- Pricing Fields (Shown when is_paid is checked) -->
                            <div id="pricing-section" class="space-y-6 @if(!old('is_paid', $event->is_paid ?? false)) hidden @endif">
                                <div class="bg-primary-50 border border-primary-200 rounded-lg p-4">
                                    <p class="text-sm text-primary-900">Enter ticket prices for different categories. All prices are optional.</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Adult Price -->
                                    <div>
                                        <label for="adult_price" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Adult Price (£)
                                        </label>
                                        <input type="number" 
                                               id="adult_price"
                                               name="adult_price" 
                                               value="{{ old('adult_price', $event->adult_price ?? '') }}"
                                               step="0.01"
                                               min="0"
                                               placeholder="25.00"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent transition">
                                        @error('adult_price')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Child Price -->
                                    <div>
                                        <label for="child_price" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Child Price (£)
                                        </label>
                                        <input type="number" 
                                               id="child_price"
                                               name="child_price" 
                                               value="{{ old('child_price', $event->child_price ?? '') }}"
                                               step="0.01"
                                               min="0"
                                               placeholder="15.00"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent transition">
                                        @error('child_price')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Concession Price -->
                                    <div>
                                        <label for="concession_price" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Concession Price (£)
                                        </label>
                                        <input type="number" 
                                               id="concession_price"
                                               name="concession_price" 
                                               value="{{ old('concession_price', $event->concession_price ?? '') }}"
                                               step="0.01"
                                               min="0"
                                               placeholder="20.00"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent transition">
                                        @error('concession_price')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tags Section -->
                    <div class="p-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Tags
                        </h2>

                        <div class="space-y-4">
                            <p class="text-sm text-gray-600">Select tags to help customers discover this event. Tags are also used for personalised recommendations.</p>
                            
                            @if(isset($tags) && $tags->count())
                                <div class="flex flex-wrap gap-3">
                                    @foreach($tags as $tag)
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" 
                                                   name="tags[]" 
                                                   value="{{ $tag->id }}"
                                                   {{ in_array($tag->id, old('tags', isset($event) ? $event->tags->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                                                   class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                            <span class="ml-2 text-sm text-gray-700">{{ $tag->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic">No tags available. <a href="{{ route('admin.tags.create') }}" class="text-primary-600 hover:underline">Create some tags first</a>.</p>
                            @endif
                            
                            @error('tags')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Media Section -->
                    <div class="p-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Event Image
                        </h2>

                        <div class="space-y-6">
                            @if(isset($event) && $event->primary_image)
                                <div>
                                    <p class="text-sm font-medium text-gray-900 mb-3">Current Image</p>
                                    <img src="{{ asset('storage/' . $event->primary_image) }}" 
                                         alt="{{ $event->title }}"
                                         class="w-full max-w-xs h-48 object-cover rounded-lg border border-gray-300">
                                </div>
                            @endif

                            <div>
                                <label for="primary_image" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Upload Main Event Image
                                </label>
                                
                                <!-- Image Preview Container (hidden by default) -->
                                <div id="imagePreviewContainer" class="hidden mb-4">
                                    <p class="text-sm font-medium text-gray-900 mb-2">New Image Preview</p>
                                    <div class="relative inline-block">
                                        <img id="imagePreview" src="" alt="Preview" class="w-full max-w-xs h-48 object-cover rounded-lg border border-gray-300">
                                        <button type="button" onclick="clearImagePreview()" class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1.5 hover:bg-red-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <div id="uploadBox" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary-400 transition cursor-pointer" onclick="document.getElementById('primary_image').click()">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <input type="file" 
                                           id="primary_image"
                                           name="primary_image" 
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                           class="hidden"
                                           onchange="previewImage(this)">
                                    <div class="pointer-events-none">
                                        <span class="text-gray-600">Click to upload or drag and drop</span>
                                        <p class="text-sm text-gray-500 mt-1">PNG, JPG, GIF or WebP. Max 5MB</p>
                                    </div>
                                </div>
                                @error('primary_image')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div class="p-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Event Status
                        </h2>

                        <div class="space-y-4">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <label for="status" class="block text-sm font-semibold text-gray-900 mb-3">
                                    Status
                                </label>
                                <select id="status" 
                                        name="status" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent transition">
                                    <option value="draft" @selected(old('status', $event->status ?? 'draft') === 'draft')>Draft</option>
                                    <option value="active" @selected(old('status', $event->status ?? null) === 'active')>Active</option>
                                    <option value="cancelled" @selected(old('status', $event->status ?? null) === 'cancelled')>Cancelled</option>
                                </select>
                                <p class="mt-2 text-sm text-gray-600">
                                    <strong>Draft:</strong> Not visible to public | 
                                    <strong>Active:</strong> Open for bookings | 
                                    <strong>Cancelled:</strong> Visible but bookings closed
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="bg-gray-50 border-t border-gray-200 px-8 py-6 flex justify-between gap-4">
                        <a href="{{ route('admin.events.index') }}" 
                           class="inline-flex items-center px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-8 py-2 bg-gradient-to-r from-primary-700 to-primary-600 text-white font-semibold rounded-lg hover:from-primary-800 hover:to-primary-700 transition shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            @isset($event)
                                Save Changes
                            @else
                                Create Event
                            @endisset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle pricing section based on is_paid checkbox
        document.getElementById('is_paid').addEventListener('change', function() {
            const pricingSection = document.getElementById('pricing-section');
            if (this.checked) {
                pricingSection.classList.remove('hidden');
            } else {
                pricingSection.classList.add('hidden');
            }
        });

        // Image preview
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreviewContainer').classList.remove('hidden');
                    document.getElementById('uploadBox').classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Clear image preview
        function clearImagePreview() {
            document.getElementById('primary_image').value = '';
            document.getElementById('imagePreview').src = '';
            document.getElementById('imagePreviewContainer').classList.add('hidden');
            document.getElementById('uploadBox').classList.remove('hidden');
        }
    </script>
</x-app-layout>

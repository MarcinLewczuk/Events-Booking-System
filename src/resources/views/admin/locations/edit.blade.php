<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Edit Location</h1>
                <p class="mt-1 text-sm text-gray-600">Update location details</p>
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

                    <!-- Usage Stats -->
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-600 to-red-700 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $location->name }}</h3>
                                <p class="text-sm text-gray-600">
                                    Hosting <span class="font-semibold text-red-600">{{ $location->auctions()->count() }}</span> 
                                    {{ Str::plural('auction', $location->auctions()->count()) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.locations.update', $location) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Location Name -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Location Name <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $location->name) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition @error('name') border-red-500 @enderror"
                                   required>
                        </div>

                        <!-- Address -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Address
                            </label>
                            <textarea 
                                name="address" 
                                rows="3"
                                placeholder="e.g., London Road, Northampton, NN4 8AW"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition @error('address') border-red-500 @enderror">{{ old('address', $location->address) }}</textarea>
                            <p class="mt-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Full address including street, city, and postcode
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea 
                                name="description" 
                                rows="4"
                                placeholder="e.g., Historic auction house with modern facilities, parking available, wheelchair accessible..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition @error('description') border-red-500 @enderror">{{ old('description', $location->description) }}</textarea>
                            <p class="mt-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Additional details about the venue, facilities, or access information
                            </p>
                        </div>

                        <!-- Current Image -->
                        @if($location->image_path)
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Current Image
                                </label>
                                <div class="relative inline-block">
                                    <img src="{{ asset('storage/' . $location->image_path) }}" 
                                         alt="{{ $location->name }}" 
                                         class="w-48 h-48 object-cover rounded-lg border-2 border-gray-200">
                                </div>
                            </div>
                        @endif

                        <!-- Image Upload -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $location->image_path ? 'Replace Image' : 'Location Image' }}
                            </label>
                            <input type="file" 
                                   name="image" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition @error('image') border-red-500 @enderror">
                            <p class="mt-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Upload an image of the venue (JPEG, PNG, GIF, WebP - Max 5MB)
                            </p>
                        </div>

                        <!-- Max Attendance -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Maximum Attendance
                            </label>
                            <input type="number" 
                                   name="max_attendance" 
                                   value="{{ old('max_attendance', $location->max_attendance) }}"
                                   min="0"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent transition @error('max_attendance') border-red-500 @enderror">
                            <p class="mt-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Leave empty for unlimited capacity
                            </p>
                        </div>
                        <!-- Seating Configuration -->
                        <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-lg">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Seating Configuration
                            </h3>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Seating Rows -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Number of Rows
                                    </label>
                                    <input type="number" 
                                        name="seating_rows" 
                                        value="{{ old('seating_rows', $location->seating_rows ?? 10) }}"
                                        min="1"
                                        max="26"
                                        placeholder="10"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent transition @error('seating_rows') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-600">Rows will be labeled A-Z</p>
                                </div>

                                <!-- Seating Columns -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Seats per Row
                                    </label>
                                    <input type="number" 
                                        name="seating_columns" 
                                        value="{{ old('seating_columns', $location->seating_columns ?? 10) }}"
                                        min="1"
                                        max="100"
                                        placeholder="10"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent transition @error('seating_columns') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-600">Number of seats in each row</p>
                                </div>
                            </div>

                            <div class="mt-3 p-3 bg-purple-100 rounded-lg">
                                <p class="text-xs text-purple-800">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <strong>Note:</strong> If you enter seating configuration, max attendance will be automatically calculated (rows × columns).
                                </p>
                            </div>
                        </div>
                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                            <x-buttons.primary type="submit">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Location
                            </x-buttons.primary>
                            <x-buttons.secondary href="{{ route('admin.locations.index') }}">
                                Cancel
                            </x-buttons.secondary>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Section -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-red-200">
                <div class="border-l-4 border-red-600 p-6">
                    <h3 class="text-lg font-semibold text-red-900 mb-2">Delete Location</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Once deleted, this location cannot be recovered. This action is only available if no auctions or items are assigned to this location.
                    </p>
                    
                    @if($location->auctions()->count() > 0 || $location->items()->count() > 0)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-yellow-800">Cannot delete this location</p>
                                    <p class="text-sm text-yellow-700 mt-1">
                                        This location is assigned to {{ $location->auctions()->count() }} {{ Str::plural('auction', $location->auctions()->count()) }} 
                                        and {{ $location->items()->count() }} {{ Str::plural('item', $location->items()->count()) }}.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <form method="POST" action="{{ route('admin.locations.destroy', $location) }}" onsubmit="return confirm('Are you sure you want to delete this location? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <x-buttons.danger type="submit">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete Location
                            </x-buttons.danger>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
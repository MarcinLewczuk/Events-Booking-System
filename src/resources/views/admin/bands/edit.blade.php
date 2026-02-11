
<x-app-layout>
    <div class="py-8 bg-gradient-to-br from-primary-50 to-secondary-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border-l-4 border-indigo-600">
                <div class="p-6">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-primary-900">Edit Price Band: {{ $band->name }}</h1>
                        <p class="text-gray-600 mt-1">Update price band details</p>
                    </div>

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-red-800 font-medium mb-2">Please fix the following errors:</p>
                                    <ul class="list-disc list-inside text-red-700">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.bands.update', $band) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Band Name <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $band->name) }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea name="description" 
                                      rows="3"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('description', $band->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Minimum Price (£) <span class="text-red-600">*</span>
                                </label>
                                <input type="number" 
                                       name="min_price" 
                                       value="{{ old('min_price', $band->min_price) }}"
                                       step="0.01"
                                       min="0"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Maximum Price (£) <span class="text-gray-500 text-xs">(Optional)</span>
                                </label>
                                <input type="number" 
                                       name="max_price" 
                                       value="{{ old('max_price', $band->max_price) }}"
                                       step="0.01"
                                       min="0"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <input type="checkbox" 
                                   name="requires_approval" 
                                   id="requires_approval"
                                   value="1"
                                   {{ old('requires_approval', $band->requires_approval) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <label for="requires_approval" class="ml-3 text-sm font-medium text-gray-700">
                                Items in this band require approval before listing
                            </label>
                        </div>

                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <strong>Items using this band:</strong> {{ $band->items()->count() }}
                            </p>
                        </div>

                        <div class="flex gap-3 pt-4 border-t">
                            <x-buttons.primary type="submit">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Band
                            </x-buttons.primary>
                            <a href="{{ route('admin.bands.index') }}" 
                               class="inline-flex items-center px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg shadow-md transition">
                                Cancel
                            </a>
                        </div>
                    </form>

                    <!-- Delete Section -->
                    <form method="POST" 
                          action="{{ route('admin.bands.destroy', $band) }}"
                          class="mt-8 pt-6 border-t"
                          onsubmit="return confirm('Are you sure you want to delete this band? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <div class="flex items-start gap-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">Delete Price Band</h3>
                                <p class="text-sm text-gray-600">Once deleted, this band cannot be recovered. Items using this band will need to be reassigned.</p>
                            </div>
                            <x-buttons.danger type="submit">
                                Delete Band
                            </x-buttons.danger>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
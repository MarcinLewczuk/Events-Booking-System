<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Create New Tag</h1>
                <p class="mt-1 text-sm text-gray-600">Add a new tag to organise your events</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="border-l-4 border-[#370671] p-6">
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

                    <form method="POST" action="{{ route('admin.tags.store') }}">
                        @csrf

                        <!-- Tag Name -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tag Name <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   placeholder="e.g., Virtual, In-Person, Family-Friendly, Beginner"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition @error('name') border-red-500 @enderror"
                                   required>
                            <p class="mt-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                A unique identifier for this tag (slug will be auto-generated)
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" 
                                      rows="4" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition @error('description') border-red-500 @enderror"
                                      placeholder="Optional description to help staff understand when to use this tag">{{ old('description') }}</textarea>
                            <p class="mt-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Optional: Add context for when staff should apply this tag to events
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                            <x-buttons.primary type="submit">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Create Tag
                            </x-buttons.primary>
                            <x-buttons.secondary href="{{ route('admin.tags.index') }}">
                                Cancel
                            </x-buttons.secondary>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Tag Preferences</h1>
                <p class="mt-1 text-sm text-gray-600">Select tags you're interested in to personalize your experience</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-green-800 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Tag Selection Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="border-l-4 border-primary-600 p-6">
                    <form method="POST" action="{{ route('profile.tags.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <p class="text-sm text-gray-600 mb-4">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Select the tags that interest you. We'll use this to show you relevant items and auctions.
                            </p>

                            @if($allTags->isEmpty())
                                <div class="text-center py-8">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <p class="text-gray-500">No tags available yet</p>
                                </div>
                            @else

                                <!-- Search Bar -->
                                <div class="mb-4">
                                    <div class="flex gap-2">
                                        <input
                                            type="text"
                                            id="tagSearch"
                                            placeholder="Search tags..."
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent"
                                        >
                                        <button
                                            type="button"
                                            onclick="filterTags()"
                                            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition"
                                        >
                                            Search
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                    @foreach($allTags as $tag)
                                        <label
                                            data-tag-name="{{ strtolower($tag->name) }}"
                                            class="relative flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-primary-50 transition-colors
                                            {{ in_array($tag->id, $userTagIds) ? 'bg-primary-50 border-primary-600 ring-2 ring-primary-600' : '' }}">
                                            <input type="checkbox" 
                                                   name="tags[]" 
                                                   value="{{ $tag->id }}"
                                                   {{ in_array($tag->id, $userTagIds) ? 'checked' : '' }}
                                                   class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-600">
                                            <span class="ml-2 text-sm font-medium text-gray-900">
                                                {{ $tag->name }}
                                            </span>
                                            @if(in_array($tag->id, $userTagIds))
                                                <svg class="w-4 h-4 ml-auto text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
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
                                Save Preferences
                            </x-buttons.primary>
                            <x-buttons.secondary href="{{ route('profile.edit') }}">
                                Back to Profile
                            </x-buttons.secondary>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistics Card -->
            @if($userTagIds)
                <div class="mt-6 bg-primary-50 border border-primary-200 rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 mb-2">Your Interests</h3>
                    <p class="text-sm text-gray-600">
                        You're following <span class="font-bold text-primary-600">{{ count($userTagIds) }}</span> 
                        {{ Str::plural('tag', count($userTagIds)) }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function filterTags() {
            const search = document.getElementById('tagSearch').value.toLowerCase();
            const tags = document.querySelectorAll('[data-tag-name]');

            tags.forEach(tag => {
                const name = tag.dataset.tagName;
                tag.style.display = name.includes(search) ? 'flex' : 'none';
            });
        }

        // Optional: live search as user types
        document.getElementById('tagSearch').addEventListener('input', filterTags);
    </script>
</x-app-layout>

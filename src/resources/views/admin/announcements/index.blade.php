
<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="bg-white shadow-lg p-6 mb-6 border-l-4 border-primary-900">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Announcements</h1>
                        <p class="mt-1 text-sm text-gray-600">Manage event and general announcements</p>
                    </div>
                    <x-buttons.primary href="{{ route('admin.announcements.create') }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        New Announcement
                    </x-buttons.primary>
                </div>
            </div>

            <!-- Filters -->
            <div class="mb-6 bg-white shadow-sm border border-gray-200 p-4">
                <form method="GET" action="{{ route('admin.announcements.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search announcements..."
                           class="flex-1 px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                    <select name="topic" class="px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                        <option value="">All Topics</option>
                        <option value="event" {{ request('topic') == 'event' ? 'selected' : '' }}>Event-Specific</option>
                        <option value="general" {{ request('topic') == 'general' ? 'selected' : '' }}>General</option>
                    </select>
                    <x-buttons.primary type="submit">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search
                    </x-buttons.primary>
                    @if(request('search') || request('topic'))
                        <a href="{{ route('admin.announcements.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold transition text-center">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-green-800 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if($announcements->isEmpty())
                <!-- Empty State -->
                <div class="bg-white shadow-sm border border-gray-200 p-12 text-center">
                    <div class="w-16 h-16 bg-purple-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Announcements</h3>
                    <p class="text-gray-600 mb-4">Get started by creating your first announcement.</p>
                    <x-buttons.primary href="{{ route('admin.announcements.create') }}">
                        Create Announcement
                    </x-buttons.primary>
                </div>
            @else
                <!-- Announcements List -->
                <div class="space-y-4">
                    @foreach($announcements as $announcement)
                        <div class="bg-white shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-xl font-semibold text-gray-900">{{ $announcement->title }}</h3>
                                            @if($announcement->topic === 'event')
                                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold">
                                                    🎉 Event
                                                </span>
                                            @else
                                                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold">
                                                    📢 General
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-gray-700 mb-3">{{ Str::limit($announcement->message, 150) }}</p>

                                        <div class="flex items-center gap-4 text-sm text-gray-600 flex-wrap">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                                {{ $announcement->getRelatedItemName() }}
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $announcement->creator?->first_name }} {{ $announcement->creator?->surname ?? 'Unknown' }}
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $announcement->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex gap-2 ml-4">
                                        <x-buttons.primary href="{{ route('admin.announcements.edit', $announcement) }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </x-buttons.primary>
                                        <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-buttons.danger type="submit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </x-buttons.danger>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

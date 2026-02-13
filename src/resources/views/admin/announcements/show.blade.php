
<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $announcement->title }}</h1>
                    <p class="mt-1 text-sm text-gray-600">Announcement Details</p>
                </div>
                <div class="flex gap-2">
                    <x-buttons.primary href="{{ route('admin.announcements.edit', $announcement) }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </x-buttons.primary>
                    <x-buttons.secondary href="{{ route('admin.announcements.index') }}">
                        Back to List
                    </x-buttons.secondary>
                </div>
            </div>

            <!-- Announcement Card -->
            <div class="bg-white shadow-sm border border-gray-200">
                <div class="border-l-4 border-primary-600 p-6">
                    <!-- Topic Badge -->
                    <div class="mb-4">
                        @if($announcement->topic === 'event')
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold">
                                🎉 Event-Specific
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold">
                                📢 General
                            </span>
                        @endif
                    </div>

                    <!-- Linked Event -->
                    @if($announcement->event)
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded">
                            <p class="text-sm text-blue-800">
                                <strong>Linked Event:</strong> {{ $announcement->event->title }}
                                ({{ $announcement->event->start_datetime->format('M d, Y \a\t g:i A') }})
                            </p>
                        </div>
                    @endif

                    <!-- Message -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Message</h3>
                        <div class="bg-gray-50 border border-gray-200 rounded p-4">
                            <p class="text-gray-800 whitespace-pre-wrap">{{ $announcement->message }}</p>
                        </div>
                    </div>

                    <!-- Meta Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-gray-600 pt-4 border-t border-gray-200">
                        <div>
                            <p class="font-semibold text-gray-700">Created by</p>
                            <p>{{ $announcement->creator?->first_name }} {{ $announcement->creator?->surname ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700">Created</p>
                            <p>{{ $announcement->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700">Last updated</p>
                            <p>{{ $announcement->updated_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

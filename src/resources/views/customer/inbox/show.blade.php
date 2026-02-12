<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('customer.inbox.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Inbox
                </a>
            </div>

            <!-- Announcement Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="border-l-4 border-[#370671] p-6">
                    <!-- Header -->
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <div class="flex items-center space-x-2 mb-3">
                            @if($announcement->topic === 'auction')
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded">
                                    Auction Announcement
                                </span>
                            @else
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm font-semibold rounded">
                                    Catalogue Announcement
                                </span>
                            @endif
                            <span class="text-sm text-gray-500">
                                {{ $announcement->created_at->format('F j, Y \a\t g:i A') }}
                            </span>
                        </div>
                        
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">
                            {{ $announcement->title }}
                        </h1>
                        
                        <p class="text-sm text-gray-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Regarding: <span class="font-semibold ml-1">{{ $announcement->getRelatedItemName() }}</span>
                        </p>
                    </div>

                    <!-- Message -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Message</h3>
                        <div class="prose prose-sm max-w-none">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $announcement->message }}</p>
                        </div>
                    </div>

                    <!-- Why You're Seeing This -->
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-purple-900 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Why you're seeing this
                        </h3>
                        <p class="text-sm text-purple-800 mb-3">
                            This announcement matches your interests. You have expressed interest in the following tags that relate to items in this {{ $announcement->topic }}:
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($matchingTags as $tag)
                                <span class="px-3 py-1 bg-purple-200 text-purple-900 text-xs font-semibold rounded-full">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        @if($announcement->auction_id && $announcement->auction)
                            <a href="{{ route('auctions.browse') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold rounded-lg hover:from-primary-700 hover:to-primary-800 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View Related Auction
                            </a>
                        @elseif($announcement->catalogue_id && $announcement->catalogue)
                            <a href="{{ route('items.browse') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold rounded-lg hover:from-primary-700 hover:to-primary-800 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Browse Items
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profile Settings
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Tag Preferences Card (for customers) -->
            @if(auth()->user()->role === 'customer')
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border-l-4 border-purple-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Tag Preferences</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                @if(auth()->user()->interestedTags->count() > 0)
                                    You're following {{ auth()->user()->interestedTags->count() }} tag(s). Manage your interests to get personalized recommendations.
                                @else
                                    Add tags to get personalized recommendations for items, catalogues, and auctions.
                                @endif
                            </p>
                            @if(auth()->user()->interestedTags->count() > 0)
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach(auth()->user()->interestedTags->take(5) as $tag)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-200 text-primary-800">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                    @if(auth()->user()->interestedTags->count() > 5)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-200 text-gray-700">
                                            +{{ auth()->user()->interestedTags->count() - 5 }} more
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div>
                            <x-buttons.primary href="{{ route('profile.tags') }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Manage Tags
                            </x-buttons.primary>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Profile Information -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
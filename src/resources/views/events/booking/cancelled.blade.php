<x-layouts.app>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                <svg class="w-24 h-24 text-red-500 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Event Cancelled</h1>
                
                <h2 class="text-2xl font-semibold text-gray-700 mb-6">{{ $event->title }}</h2>
                
                <p class="text-lg text-gray-600 mb-8">
                    Unfortunately, this event has been cancelled and is no longer available for booking.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('events') }}" 
                       class="inline-block bg-teal-light-400 hover:bg-primary-700 text-white font-bold py-3 px-8 rounded-lg transition">
                        Browse Other Events
                    </a>
                    
                    <a href="{{ route('home') }}" 
                       class="inline-block border-2 border-gray-300 hover:border-gray-400 text-gray-700 font-bold py-3 px-8 rounded-lg transition">
                        Return Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

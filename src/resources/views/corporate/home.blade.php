<x-layouts.app>
    <!-- Hero Section - Heritage Venue Style -->
    <div class="relative bg-gray-900 overflow-hidden">
        <div class="relative h-[600px]">
            <!-- Background gradient (placeholder for actual image) -->
            <div class="w-full h-full bg-gradient-to-br from-primary-800 via-primary-700 to-secondary-600"></div>
            
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
            
            <!-- Content -->
            <div class="absolute inset-0 flex items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-2xl">
                        <h1 class="text-6xl font-bold text-white mb-6">
                            Welcome to<br>Delapré Abbey
                        </h1>
                        <p class="text-2xl text-gray-100 mb-8 leading-relaxed">
                            900 years of history waiting to be explored. Discover events, exhibitions, and the beauty of this Grade II* listed building.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('events') }}" 
                               class="inline-block bg-secondary-500 hover:bg-secondary-600 text-gray-900 font-bold py-4 px-8 rounded-lg transition text-lg shadow-lg">
                                What's On
                            </a>
                            <a href="{{ route('locations.index') }}" 
                               class="inline-block border-2 border-white text-white hover:bg-white/10 font-bold py-4 px-8 rounded-lg transition text-lg">
                                Plan Your Visit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

                <!-- Quick Info Bar -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="flex flex-col items-center">
                    <svg class="w-10 h-10 text-primary-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="font-bold text-gray-900 mb-1">Opening Times</h3>
                    <p class="text-gray-600">Daily 10:00 AM - 4:00 PM</p>
                </div>
                <div class="flex flex-col items-center">
                    <svg class="w-10 h-10 text-primary-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <h3 class="font-bold text-gray-900 mb-1">Location</h3>
                    <p class="text-gray-600">London Road, Northampton</p>
                </div>
                <div class="flex flex-col items-center">
                    <svg class="w-10 h-10 text-primary-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <h3 class="font-bold text-gray-900 mb-1">Contact</h3>
                    <p class="text-gray-600">01604 760817</p>
                </div>
            </div>
        </div>
    </div>
    <!-- What's On Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">What's On</h2>
                <p class="text-xl text-gray-600">Upcoming events and experiences</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- Event 1 -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition group">
                    <div class="relative h-64 bg-gradient-to-br from-primary-200 to-primary-300">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-24 h-24 text-primary-600 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="absolute top-4 left-4 bg-secondary-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            14 FEB
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-primary-600 font-semibold mb-2">SPECIAL EVENT</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition">
                            Valentine's Murder Mystery
                        </h3>
                        <p class="text-gray-600 mb-4">
                            An evening of intrigue, dinner, and mystery set in the historic abbey.
                        </p>
                        <a href="{{ route('events.show', 1) }}" class="text-primary-600 hover:text-primary-700 font-semibold">
                            Learn More →
                        </a>
                    </div>
                </div>

                <!-- Event 2 -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition group">
                    <div class="relative h-64 bg-gradient-to-br from-secondary-200 to-secondary-300">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-24 h-24 text-secondary-700 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div class="absolute top-4 left-4 bg-secondary-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            ONGOING
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-secondary-600 font-semibold mb-2">EXHIBITION</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition">
                            Indoor Flea Market
                        </h3>
                        <p class="text-gray-600 mb-4">
                            Browse unique treasures and vintage finds at our monthly market.
                        </p>
                        <a href="{{ route('events.show', 2) }}" class="text-primary-600 hover:text-primary-700 font-semibold">
                            Learn More →
                        </a>
                    </div>
                </div>

                <!-- Event 3 -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition group">
                    <div class="relative h-64 bg-gradient-to-br from-green-200 to-green-300">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-24 h-24 text-green-700 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="absolute top-4 left-4 bg-primary-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            18 FEB
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-green-600 font-semibold mb-2">WORKSHOP</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition">
                            Heritage Crafts Workshop
                        </h3>
                        <p class="text-gray-600 mb-4">
                            Learn traditional crafting techniques in a hands-on workshop.
                        </p>
                        <a href="{{ route('events.show', 3) }}" class="text-primary-600 hover:text-primary-700 font-semibold">
                            Learn More →
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('events') }}" class="inline-block bg-secondary-500 hover:bg-secondary-600 text-gray-900 font-bold py-3 px-8 rounded-lg transition shadow-lg">
                    See All Events →
                </a>
            </div>
        </div>
    </section>

    <div class="bg-gray-50">


        <!-- Things to See and Do -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Things to See and Do</h2>
                    <p class="text-xl text-gray-600">Explore the abbey and its surroundings</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Explore the House -->
                    <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-lg p-8 hover:shadow-lg transition">
                        <div class="text-primary-600 mb-4">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Explore the House</h3>
                        <p class="text-gray-700 mb-4">
                            Discover 900 years of history inside this Grade II* listed building. Under 18s go free.
                        </p>
                        <a href="{{ route('items.browse') }}" class="text-primary-600 hover:text-primary-700 font-semibold">
                            Learn More →
                        </a>
                    </div>

                    <!-- Heritage Tours -->
                    <div class="bg-gradient-to-br from-secondary-50 to-secondary-100 rounded-lg p-8 hover:shadow-lg transition">
                        <div class="text-secondary-700 mb-4">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Guided Tours</h3>
                        <p class="text-gray-700 mb-4">
                            Join our expert guides for an in-depth exploration of the abbey's fascinating history.
                        </p>
                        <a href="{{ route('events') }}" class="text-primary-600 hover:text-primary-700 font-semibold">
                            Book a Tour →
                        </a>
                    </div>

                    <!-- The Gardens -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-8 hover:shadow-lg transition">
                        <div class="text-green-700 mb-4">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">The Gardens</h3>
                        <p class="text-gray-700 mb-4">
                            Wander through our beautiful historic gardens and enjoy peaceful walks.
                        </p>
                        <a href="{{ route('locations.index') }}" class="text-primary-600 hover:text-primary-700 font-semibold">
                            Explore →
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Find Us</h2>
                    <p class="text-xl text-gray-600">Located in the heart of Northampton</p>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Delapré Abbey</h3>
                        <div class="space-y-4 text-gray-700">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-primary-600 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Address</h4>
                                    <p>London Road, Northampton NN4 8AW</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-primary-600 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Phone</h4>
                                    <p>01604 760817</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-primary-600 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Opening Times</h4>
                                    <p>Daily 10:00 AM - 4:00 PM</p>
                                </div>
                            </div>
                        </div>
                        <a href="https://maps.google.com" target="_blank" class="inline-block mt-6 bg-secondary-500 hover:bg-secondary-600 text-gray-900 font-bold py-3 px-6 rounded-lg transition shadow-lg">
                            Get Directions →
                        </a>
                    </div>
                    <div class="bg-gray-200 rounded-lg overflow-hidden h-96">
                        <iframe width="100%" height="100%" style="border:0" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2419.047!2d-0.8897870554191317!3d52.22615116781254!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487630d4c5c5c5c5%3A0x5c5b5c5c5c5c5c5c!2sDelap%C3%A9%20Abbey!5e0!3m2!1sen!2suk!4v1234567890123" width="600" height="450"></iframe>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="bg-primary-500 text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-4xl font-bold mb-6">900 Years of History</h2>
                        <p class="text-lg text-white/90 mb-6 leading-relaxed">
                            Founded in 1145 as a Cluniac nunnery, Delapré Abbey has witnessed centuries of history. From medieval monastery to country house, and now a thriving heritage venue, the abbey continues to be a place where history comes alive.
                        </p>
                        <p class="text-lg text-white/90 mb-8 leading-relaxed">
                            Today, we welcome visitors from around the world to explore our beautiful building, attend events, enjoy fine dining, and discover the stories that make this place so special.
                        </p>
                        <a href="{{ route('about') }}" class="inline-block bg-secondary-500 hover:bg-secondary-600 text-gray-900 font-bold py-3 px-6 rounded-lg transition shadow-lg">
                            Our Story →
                        </a>
                    </div>
                    <div class="relative h-96 bg-primary-600 rounded-lg overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-32 h-32 text-primary-200 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <!-- Visit Us Section -->
        @if($locations->isNotEmpty())
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Plan Your Visit</h2>
                    <p class="text-xl text-gray-600">Find us and explore our historic spaces</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($locations->take(3) as $location)
                        <a href="{{ route('locations.index') }}" class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition group">
                            @if($location->image_path)
                                <div class="relative h-64 overflow-hidden">
                                    <img src="{{ asset('storage/' . $location->image_path) }}" 
                                         alt="{{ $location->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                            @else
                                <div class="relative h-64 bg-gradient-to-br from-primary-200 to-primary-300">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-24 h-24 text-primary-600 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            @endif
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-primary-600 transition">
                                    {{ $location->name }}
                                </h3>
                                @if($location->address_line1)
                                    <p class="text-gray-600">{{ $location->address_line1 }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="text-center mt-8">
                    <a href="{{ route('locations.index') }}" class="inline-block bg-secondary-500 hover:bg-secondary-600 text-gray-900 font-bold py-3 px-8 rounded-lg transition shadow-lg">
                        View All Locations →
                    </a>
                </div>
            </div>
        </section>
        @endif

        <!-- Newsletter Section -->
        <section class="bg-gray-800 text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold mb-4">Stay Connected</h2>
                <p class="text-xl text-secondary-100 mb-8 max-w-2xl mx-auto">
                    Subscribe to our newsletter for updates on events, special offers, and news from Delapré Abbey
                </p>
                <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                    <input type="email" placeholder="Your email address" 
                           class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:ring-2 focus:ring-white">
                    <button class="px-8 py-3 bg-secondary-500 hover:bg-secondary-600 text-gray-900 rounded-lg transition font-bold whitespace-nowrap shadow-lg">
                        Subscribe
                    </button>
                </div>
            </div>
        </section>

    </div>
</x-layouts.app>
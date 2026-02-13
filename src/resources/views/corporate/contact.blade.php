<x-layouts.app>
    <div class="bg-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary-700 to-primary-600 text-white py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-5xl font-bold mb-4">Get in Touch</h1>
                <p class="text-xl text-primary-100">We'd love to hear from you. Whether you have a question about events, bookings, or anything else, our team is ready to help.</p>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Main Contact Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <!-- General Inquiries -->
                <div class="bg-white shadow-sm p-8 border-l-4 border-primary-700">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-primary-100 flex items-center justify-center mr-4">
                            <svg class="w-8 h-8 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Questions?</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Drop us an email</h3>
                            <a href="mailto:info@delapre.com" class="text-primary-700 hover:text-primary-800 text-lg">
                                info@delapre.com
                            </a>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Give us a call</h3>
                            <a href="tel:+441604760817" class="text-primary-700 hover:text-primary-800 text-lg">
                                +44 (0) 1604 760817
                            </a>
                            <p class="text-sm text-gray-500 mt-1">We're here Mon-Fri, 9:00 AM - 6:00 PM</p>
                        </div>
                    </div>
                </div>

                <!-- Event Ticketing & Bookings -->
                <div class="bg-white shadow-sm p-8 border-l-4 border-primary-600">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-primary-100 flex items-center justify-center mr-4">
                            <svg class="w-8 h-8 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m-4 3v2m4 3v2M9 5h6M9 8h6m-7 8a2 2 0 11-4 0 2 2 0 014 0zm0 0h12a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Event Bookings</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Email us</h3>
                            <a href="mailto:events@delapre.com" class="text-primary-700 hover:text-primary-800 text-lg">
                                events@delapre.com
                            </a>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Call us</h3>
                            <a href="mailto:tel:+441604760817" class="text-primary-700 hover:text-primary-800 text-lg">
                                +44 (0) 1604 760817
                            </a>
                            <p class="text-sm text-gray-500 mt-1">Open daily, 10:00 AM - 4:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Head Office Address -->
            <div class="bg-white shadow-sm p-8 mb-12">
                <div class="flex items-start">
                    <div class="w-16 h-16 bg-red-100 flex items-center justify-center mr-6 flex-shrink-0">
                        <svg class="w-8 h-8 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Visit Us</h2>
                        <address class="not-italic text-gray-700 text-lg space-y-1">
                            <p class="font-semibold">Delapré Abbey</p>
                            <p>London Road</p>
                            <p>Northampton</p>
                            <p>NN4 8AW</p>
                            <p>United Kingdom</p>
                        </address>
                        <div class="mt-6">
                            <a href="{{ route('locations.index') }}" class="inline-block text-primary-700 hover:text-primary-800 font-semibold">
                                View Directions →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Contacts -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Need Something Specific?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Event Coordination -->
                    <div class="bg-white shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-3">Event Planning</h3>
                        <p class="text-sm text-gray-600 mb-3">Help planning your perfect event</p>
                        <a href="mailto:events@delapre.com" class="text-primary-700 hover:text-primary-800 text-sm font-medium">
                            events@delapre.com
                        </a>
                    </div>

                    <!-- Booking Support -->
                    <div class="bg-white shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-3">Booking Help</h3>
                        <p class="text-sm text-gray-600 mb-3">Questions about your tickets or reservation</p>
                        <a href="mailto:bookings@delapre.com" class="text-primary-700 hover:text-primary-800 text-sm font-medium">
                            bookings@delapre.com
                        </a>
                    </div>

                    <!-- Venue Management -->
                    <div class="bg-white shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-3">Venue Hire</h3>
                        <p class="text-sm text-gray-600 mb-3">Interested in hosting your own event here?</p>
                        <a href="mailto:venue@delapre.com" class="text-primary-700 hover:text-primary-800 text-sm font-medium">
                            venue@delapre.com
                        </a>
                    </div>

                    <!-- Press & Media -->
                    <div class="bg-white shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-3">Media Inquiries</h3>
                        <p class="text-sm text-gray-600 mb-3">Press and media questions</p>
                        <a href="mailto:press@delapre.com" class="text-primary-700 hover:text-primary-800 text-sm font-medium">
                            press@delapre.com
                        </a>
                    </div>

                    <!-- Careers -->
                    <div class="bg-white shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-3">Join Our Team</h3>
                        <p class="text-sm text-gray-600 mb-3">Explore career opportunities with us</p>
                        <a href="mailto:careers@delapre.com" class="text-primary-700 hover:text-primary-800 text-sm font-medium">
                            careers@delapre.com
                        </a>
                    </div>

                    <!-- Accounts -->
                    <div class="bg-white shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-3">Accounts</h3>
                        <p class="text-sm text-gray-600 mb-3">Billing and payment questions</p>
                        <a href="mailto:accounts@delapre.com" class="text-primary-700 hover:text-primary-800 text-sm font-medium">
                            accounts@delapre.com
                        </a>
                    </div>
                </div>
            </div>

            <!-- Business Hours -->
            <div class="bg-gradient-to-r from-primary-700 to-primary-600 p-8 text-white">
                <h2 class="text-2xl font-bold mb-6 text-center">When to Visit</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                    <div>
                        <h3 class="font-semibold mb-2">Abbey Open</h3>
                        <p class="text-purple-100">Monday - Sunday</p>
                        <p class="text-lg font-bold">10:00 AM - 4:00 PM</p>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-2">Event Bookings</h3>
                        <p class="text-purple-100">Anytime online, or</p>
                        <p class="text-lg font-bold">Call us during opening hours</p>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-2">Venue Hire</h3>
                        <p class="text-purple-100">Interested in our spaces?</p>
                        <p class="text-lg font-bold">Get in touch</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

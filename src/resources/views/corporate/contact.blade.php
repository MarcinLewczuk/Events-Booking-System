<x-layouts.app>
    <div class="bg-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary-700 to-primary-600 text-white py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-5xl font-bold mb-4">Contact Us</h1>
                <p class="text-xl text-purple-100">We're here to help with all your auction inquiries</p>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            
            <!-- Main Contact Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <!-- General Inquiries -->
                <div class="bg-white rounded-lg shadow-sm p-8 border-l-4 border-primary-700">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-8 h-8 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">General Inquiries</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Email</h3>
                            <a href="mailto:info@fotherbys.com" class="text-primary-700 hover:text-primary-800 text-lg">
                                info@fotherbys.com
                            </a>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Phone</h3>
                            <a href="tel:+442071234567" class="text-primary-700 hover:text-primary-800 text-lg">
                                +44 (0) 20 7123 4567
                            </a>
                            <p class="text-sm text-gray-500 mt-1">Mon-Fri: 9:00 AM - 6:00 PM GMT</p>
                        </div>
                    </div>
                </div>

                <!-- Valuations & Consignments -->
                <div class="bg-white rounded-lg shadow-sm p-8 border-l-4 border-primary-600">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-8 h-8 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Valuations</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Email</h3>
                            <a href="mailto:valuations@fotherbys.com" class="text-primary-700 hover:text-primary-800 text-lg">
                                valuations@fotherbys.com
                            </a>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Phone</h3>
                            <a href="tel:+442071234568" class="text-primary-700 hover:text-primary-800 text-lg">
                                +44 (0) 20 7123 4568
                            </a>
                            <p class="text-sm text-gray-500 mt-1">Mon-Fri: 9:00 AM - 6:00 PM GMT</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Head Office Address -->
            <div class="bg-white rounded-lg shadow-sm p-8 mb-12">
                <div class="flex items-start">
                    <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center mr-6 flex-shrink-0">
                        <svg class="w-8 h-8 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Head Office</h2>
                        <address class="not-italic text-gray-700 text-lg space-y-1">
                            <p class="font-semibold">Fotherby's Auction House</p>
                            <p>123 Bond Street</p>
                            <p>Mayfair, London</p>
                            <p>W1S 4RF</p>
                            <p>United Kingdom</p>
                        </address>
                        <div class="mt-6">
                            <a href="{{ route('locations.index') }}" class="inline-block text-primary-700 hover:text-primary-800 font-semibold">
                                View All Our Locations →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Contacts -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Department Contacts</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Client Services -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-3">Client Services</h3>
                        <p class="text-sm text-gray-600 mb-3">Account assistance and general support</p>
                        <a href="mailto:clientservices@fotherbys.com" class="text-primary-700 hover:text-primary-800 text-sm font-medium">
                            clientservices@fotherbys.com
                        </a>
                    </div>

                    <!-- Bidding Support -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-3">Bidding Support</h3>
                        <p class="text-sm text-gray-600 mb-3">Help with placing bids and auction registration</p>
                        <a href="mailto:bids@fotherbys.com" class="text-primary-700 hover:text-primary-800 text-sm font-medium">
                            bids@fotherbys.com
                        </a>
                    </div>

                    <!-- Shipping & Logistics -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-3">Shipping & Logistics</h3>
                        <p class="text-sm text-gray-600 mb-3">Collection and delivery inquiries</p>
                        <a href="mailto:logistics@fotherbys.com" class="text-primary-700 hover:text-primary-800 text-sm font-medium">
                            logistics@fotherbys.com
                        </a>
                    </div>

                    <!-- Press & Media -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-3">Press & Media</h3>
                        <p class="text-sm text-gray-600 mb-3">Media inquiries and press releases</p>
                        <a href="mailto:press@fotherbys.com" class="text-primary-700 hover:text-primary-800 text-sm font-medium">
                            press@fotherbys.com
                        </a>
                    </div>

                    <!-- Careers -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-3">Careers</h3>
                        <p class="text-sm text-gray-600 mb-3">Job opportunities and applications</p>
                        <a href="mailto:careers@fotherbys.com" class="text-primary-700 hover:text-primary-800 text-sm font-medium">
                            careers@fotherbys.com
                        </a>
                    </div>

                    <!-- Accounts -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-3">Accounts</h3>
                        <p class="text-sm text-gray-600 mb-3">Payment and invoice inquiries</p>
                        <a href="mailto:accounts@fotherbys.com" class="text-primary-700 hover:text-primary-800 text-sm font-medium">
                            accounts@fotherbys.com
                        </a>
                    </div>
                </div>
            </div>

            <!-- Business Hours -->
            <div class="bg-gradient-to-r from-primary-700 to-primary-600 rounded-lg p-8 text-white">
                <h2 class="text-2xl font-bold mb-6 text-center">Business Hours</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                    <div>
                        <h3 class="font-semibold mb-2">Office Hours</h3>
                        <p class="text-purple-100">Monday - Friday</p>
                        <p class="text-lg font-bold">9:00 AM - 6:00 PM</p>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-2">Viewing Days</h3>
                        <p class="text-purple-100">By Appointment</p>
                        <p class="text-lg font-bold">Call to Schedule</p>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-2">Weekend</h3>
                        <p class="text-purple-100">Saturday & Sunday</p>
                        <p class="text-lg font-bold">Closed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
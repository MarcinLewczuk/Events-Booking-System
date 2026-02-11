<x-layouts.app>
    <div class="bg-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary-700 to-primary-600 text-white py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-5xl font-bold mb-4">How to Bid</h1>
                <p class="text-xl text-purple-100">Your guide to participating in our auctions</p>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            
            <!-- Introduction -->
            <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
                <p class="text-lg text-gray-700 leading-relaxed">
                    Bidding at Fotherby's is straightforward and accessible to everyone. Whether you're a first-time bidder or an experienced collector, we're here to make your auction experience seamless and enjoyable.
                </p>
            </div>

            <!-- Steps -->
            <div class="space-y-6 mb-12">
                <!-- Step 1 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-primary-700 text-white px-8 py-4">
                        <h2 class="text-2xl font-bold flex items-center">
                            <span class="w-10 h-10 bg-white text-primary-700 rounded-full flex items-center justify-center mr-4 font-bold">1</span>
                            Register to Bid
                        </h2>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-700 mb-4">
                            Create an account on our website or register in person at any of our locations. You'll need to provide:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                            <li>Valid photo identification (passport or driving licence)</li>
                            <li>Proof of address (utility bill or bank statement)</li>
                            <li>Contact details and payment information</li>
                        </ul>
                        <div class="mt-6">
                            <a href="{{ route('register') }}" class="inline-block bg-primary-700 hover:bg-primary-800 text-white font-semibold py-3 px-6 rounded-lg transition">
                                Register Now →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-primary-700 text-white px-8 py-4">
                        <h2 class="text-2xl font-bold flex items-center">
                            <span class="w-10 h-10 bg-white text-primary-700 rounded-full flex items-center justify-center mr-4 font-bold">2</span>
                            View the Catalogue
                        </h2>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-700 mb-4">
                            Browse our online catalogues to view upcoming lots. Each item includes:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                            <li>Detailed descriptions and condition reports</li>
                            <li>High-quality photographs</li>
                            <li>Estimated price ranges</li>
                            <li>Auction date and lot number</li>
                        </ul>
                        <div class="mt-6">
                            <a href="{{ route('auctions.browse') }}" class="inline-block bg-primary-700 hover:bg-primary-800 text-white font-semibold py-3 px-6 rounded-lg transition">
                                Browse Auctions →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-primary-700 text-white px-8 py-4">
                        <h2 class="text-2xl font-bold flex items-center">
                            <span class="w-10 h-10 bg-white text-primary-700 rounded-full flex items-center justify-center mr-4 font-bold">3</span>
                            Place Your Bid
                        </h2>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-700 mb-4">
                            You can bid in multiple ways:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                            <div class="border-2 border-primary-200 rounded-lg p-4 text-center">
                                <svg class="w-12 h-12 text-primary-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <h3 class="font-bold text-gray-900 mb-2">In Person</h3>
                                <p class="text-sm text-gray-600">Attend the auction and bid in the saleroom</p>
                            </div>
                            <div class="border-2 border-primary-200 rounded-lg p-4 text-center">
                                <svg class="w-12 h-12 text-primary-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <h3 class="font-bold text-gray-900 mb-2">Commission Bidding</h3>
                                <p class="text-sm text-gray-600">If your unable to attend you may make use of Commission bidding</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-primary-700 text-white px-8 py-4">
                        <h2 class="text-2xl font-bold flex items-center">
                            <span class="w-10 h-10 bg-white text-primary-700 rounded-full flex items-center justify-center mr-4 font-bold">4</span>
                            Win & Pay
                        </h2>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-700 mb-4">
                            If your bid is successful:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                            <li>You'll receive confirmation immediately after the sale</li>
                            <li>Payment is due within 5 working days</li>
                            <li>We accept bank transfer, credit/debit cards, and cash (up to £10,000)</li>
                            <li>Buyer's premium of 25% applies to all lots</li>
                        </ul>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-primary-700 text-white px-8 py-4">
                        <h2 class="text-2xl font-bold flex items-center">
                            <span class="w-10 h-10 bg-white text-primary-700 rounded-full flex items-center justify-center mr-4 font-bold">5</span>
                            Collect Your Item
                        </h2>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-700 mb-4">
                            Once payment has cleared:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                            <li>Arrange collection from our warehouse</li>
                            <li>We offer packing and shipping services (fees apply)</li>
                            <li>Items must be collected within 10 working days</li>
                            <li>Storage fees may apply for late collection</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="bg-gradient-to-r from-primary-700 to-primary-600 rounded-lg p-8 text-white text-center">
                <h2 class="text-3xl font-bold mb-4">Need Help?</h2>
                <p class="text-lg text-purple-100 mb-6">
                    Our team is here to assist you with any questions about the bidding process
                </p>
                <a href="{{ route('contact') }}" class="inline-block bg-white text-primary-700 hover:bg-gray-100 font-semibold py-3 px-8 rounded-lg transition">
                    Contact Us →
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
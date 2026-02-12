<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-teal-light-50 via-white to-secondary-50">
        
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            
            <!-- Large Confirmation Box -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-8" id="receipt">
                
                <!-- Success Header -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-12 text-center print:bg-green-600">
                    <svg class="w-24 h-24 mx-auto mb-4 print:w-16 print:h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h1 class="text-4xl md:text-5xl font-bold mb-3 print:text-3xl">Booking Confirmed!</h1>
                    <p class="text-xl text-green-50 print:text-lg">Your spot is reserved</p>
                </div>

                <!-- Booking Details -->
                <div class="px-8 py-8 space-y-6">
                    
                    <!-- Booking Reference -->
                    <div class="bg-gradient-to-r from-secondary-100 to-secondary-50 border-2 border-secondary-300 rounded-xl p-6 text-center">
                        <p class="text-sm font-semibold text-gray-700 mb-1">Booking Reference</p>
                        <p class="text-3xl font-bold font-mono text-gray-900 tracking-wide print:text-2xl">{{ $booking->booking_reference }}</p>
                        <p class="text-sm text-gray-600 mt-2">Please save this reference number</p>
                    </div>

                    <!-- Booking Date/Time -->
                    <div class="flex justify-between items-center text-sm text-gray-600 pb-4 border-b">
                        <span>Booked on:</span>
                        <span class="font-semibold">{{ $booking->created_at->format('l, j F Y \a\t g:i A') }}</span>
                    </div>

                    <!-- Event Title -->
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2 print:text-2xl">{{ $booking->event->title }}</h2>
                    </div>

                    <!-- Event Date & Time -->
                    <div class="flex items-start border-l-4 border-teal-light-500 pl-4 py-2">
                        <svg class="w-6 h-6 text-teal-light-500 mr-3 mt-1 flex-shrink-0 print:w-5 print:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="font-bold text-gray-900 text-lg print:text-base">{{ $booking->event->start_datetime->format('l, j F Y') }}</p>
                            <p class="text-gray-700">{{ $booking->event->start_datetime->format('g:i A') }} - {{ $booking->event->end_datetime->format('g:i A') }}</p>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="flex items-start border-l-4 border-teal-light-500 pl-4 py-2">
                        <svg class="w-6 h-6 text-teal-light-500 mr-3 mt-1 flex-shrink-0 print:w-5 print:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <div>
                            <p class="font-bold text-gray-900">{{ $booking->event->location->name }}</p>
                            <p class="text-gray-700">{{ $booking->event->location->address }}</p>
                        </div>
                    </div>

                    <!-- Tickets Summary -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="font-bold text-gray-900 mb-4 text-lg">Your Tickets</h3>
                        <div class="space-y-2">
                            @if ($booking->adult_tickets > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-700">Adult × {{ $booking->adult_tickets }}</span>
                                    @if ($booking->event->is_paid)
                                        <span class="font-semibold text-gray-900">£{{ number_format($booking->adult_tickets * $booking->event->adult_price, 2) }}</span>
                                    @else
                                        <span class="text-green-600 font-semibold">FREE</span>
                                    @endif
                                </div>
                            @endif
                            @if ($booking->child_tickets > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-700">Child × {{ $booking->child_tickets }}</span>
                                    @if ($booking->event->is_paid)
                                        <span class="font-semibold text-gray-900">£{{ number_format($booking->child_tickets * $booking->event->child_price, 2) }}</span>
                                    @else
                                        <span class="text-green-600 font-semibold">FREE</span>
                                    @endif
                                </div>
                            @endif
                            @if ($booking->concession_tickets > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-700">Concession × {{ $booking->concession_tickets }}</span>
                                    @if ($booking->event->is_paid)
                                        <span class="font-semibold text-gray-900">£{{ number_format($booking->concession_tickets * $booking->event->concession_price, 2) }}</span>
                                    @else
                                        <span class="text-green-600 font-semibold">FREE</span>
                                    @endif
                                </div>
                            @endif
                            
                            <div class="flex justify-between pt-2 border-t border-gray-300 mt-2">
                                <span class="font-semibold text-gray-900">Total Tickets</span>
                                <span class="font-semibold text-gray-900">{{ $booking->total_tickets }}</span>
                            </div>
                            
                            @if ($booking->event->is_paid)
                                <div class="flex justify-between pt-3 border-t-2 border-gray-300 mt-3">
                                    <span class="font-bold text-gray-900 text-lg">Total Amount</span>
                                    <span class="font-bold text-teal-light-500 text-xl">£{{ number_format($booking->total_amount, 2) }}</span>
                                </div>
                                <div class="text-sm text-gray-600 mt-2">
                                    <p class="font-semibold">Payment Method: <span class="text-gray-800">{{ $booking->event->is_paid ? 'Card Payment' : 'N/A' }}</span></p>
                                </div>
                            @else
                                <div class="flex justify-between pt-3 border-t-2 border-gray-300 mt-3">
                                    <span class="font-bold text-green-600 text-lg">Entry Fee</span>
                                    <span class="font-bold text-green-600 text-xl">FREE</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Attendee Info -->
                    <div class="border-t pt-6">
                        <h3 class="font-bold text-gray-900 mb-3">Attendee Information</h3>
                        <div class="space-y-2 text-gray-700">
                            <p><span class="font-semibold">Name:</span> {{ $booking->attendee_name }}</p>
                            <p><span class="font-semibold">Email:</span> {{ $booking->attendee_email }}</p>
                            @if($booking->accessibility_notes)
                                <p><span class="font-semibold">Accessibility Notes:</span> {{ $booking->accessibility_notes }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Print Button (Hidden when printing) -->
                    <div class="pt-6 border-t print:hidden">
                        <button onclick="window.print()" 
                                class="w-full flex items-center justify-center bg-gray-800 hover:bg-gray-900 text-white font-bold py-4 px-6 rounded-lg transition shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print Receipt
                        </button>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8 space-y-4">
                <h3 class="font-bold text-gray-900 text-xl mb-4">What's Next?</h3>
                
                <!-- Add to Calendar -->
                <div class="grid sm:grid-cols-2 gap-3">
                    <a href="{{ route('events.booking.calendar', ['booking' => $booking->id, 'type' => 'google']) }}" 
                       target="_blank"
                       class="flex items-center justify-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-4 px-6 rounded-lg transition shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V9h14v10zm0-12H5V5h14v2z"/>
                        </svg>
                        Add to Google Calendar
                    </a>
                    
                    <a href="{{ route('events.booking.calendar', ['booking' => $booking->id, 'type' => 'ical']) }}" 
                       class="flex items-center justify-center bg-gradient-to-r from-secondary-500 to-secondary-600 hover:from-secondary-600 hover:to-secondary-700 text-gray-900 font-bold py-4 px-6 rounded-lg transition shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/>
                        </svg>
                        Download iCal
                    </a>
                </div>

                <!-- Manage Booking -->
                @auth
                    <a href="{{ route('events.booking.manage', $booking->id) }}" 
                       class="flex items-center justify-center border-2 border-gray-300 hover:border-primary-600 hover:bg-primary-50 text-gray-700 hover:text-primary-700 font-bold py-4 px-6 rounded-lg transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Manage Booking
                    </a>
                @endauth
            </div>

            <!-- Email Confirmation Notice -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-xl">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <h4 class="font-bold text-blue-900 mb-1">Confirmation Email Sent</h4>
                        <p class="text-blue-800 text-sm">
                            A confirmation email with all your booking details has been sent to 
                            <span class="font-semibold">{{ $booking->attendee_email }}</span>
                        </p>
                        <p class="text-blue-700 text-sm mt-2">
                            You'll also receive reminder emails 1 week and 24 hours before the event.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Browse More Events -->
            <div class="text-center mt-8">
                <a href="{{ route('events') }}" 
                   class="inline-block text-teal-light-500 hover:text-primary-700 font-semibold text-lg hover:underline">
                    ← Browse more events
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>

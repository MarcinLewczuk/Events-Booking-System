<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 bg-white rounded-lg shadow-sm p-6 border-l-4" style="border-color: #247a7c;">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">🎫 Booking Details</h1>
                        <p class="text-gray-600 mt-1">Reference: <span class="font-mono font-bold">{{ $booking->booking_reference }}</span></p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('customer.booking.download', $booking) }}" 
                           class="inline-flex items-center px-4 py-2.5 text-white font-semibold rounded-lg text-sm transition" style="background-color: #247a7c;">
                            ⬇ Download
                        </a>
                        <a href="{{ route('customer.bookings') }}" class="text-gray-600 hover:text-gray-700 font-semibold text-sm px-4 py-2.5">
                            ← Back to Bookings
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status Banner -->
            @if($booking->status === 'confirmed')
                <div class="mb-6 rounded-lg p-4 flex items-center gap-3" style="background-color: #ecfdf5; border: 1px solid #6ee7b7;">
                    <span class="text-2xl">✅</span>
                    <div>
                        <p class="font-bold text-green-800">Booking Confirmed</p>
                        <p class="text-green-700 text-sm">Your booking has been confirmed. See you there!</p>
                    </div>
                </div>
            @elseif($booking->status === 'cancelled')
                <div class="mb-6 rounded-lg p-4 flex items-center gap-3" style="background-color: #fef2f2; border: 1px solid #fca5a5;">
                    <span class="text-2xl">❌</span>
                    <div>
                        <p class="font-bold text-red-800">Booking Cancelled</p>
                        @if($booking->cancelled_at)
                            <p class="text-red-700 text-sm">Cancelled on {{ $booking->cancelled_at->format('M d, Y \a\t g:i A') }}</p>
                        @endif
                        @if($booking->refund_amount > 0)
                            <p class="text-red-700 text-sm">Refund: £{{ number_format($booking->refund_amount, 2) }}</p>
                        @endif
                    </div>
                </div>
            @elseif($booking->status === 'pending')
                <div class="mb-6 rounded-lg p-4 flex items-center gap-3" style="background-color: #fffbeb; border: 1px solid #fcd34d;">
                    <span class="text-2xl">⏳</span>
                    <div>
                        <p class="font-bold text-yellow-800">Booking Pending</p>
                        <p class="text-yellow-700 text-sm">Your booking is awaiting confirmation.</p>
                    </div>
                </div>
            @endif

            <!-- Event Info Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4" style="background-color: #247a7c;">
                    <h2 class="text-xl font-bold text-white">📅 Event Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $booking->event->title }}</h3>
                            @if($booking->event->description)
                                <p class="text-gray-600 mt-2 text-sm">{{ Str::limit($booking->event->description, 200) }}</p>
                            @endif
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <span class="text-lg">📆</span>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $booking->event->start_datetime->format('l, F j, Y') }}</p>
                                    <p class="text-gray-600 text-sm">
                                        {{ $booking->event->start_datetime->format('g:i A') }}
                                        @if($booking->event->end_datetime)
                                            – {{ $booking->event->end_datetime->format('g:i A') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-lg">📍</span>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $booking->event->location->name ?? 'TBA' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendee Info -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gray-800">
                    <h2 class="text-xl font-bold text-white">👤 Attendee Details</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Name</p>
                            <p class="font-semibold text-gray-900">
                                {{ $booking->user ? $booking->user->first_name . ' ' . $booking->user->surname : $booking->guest_first_name . ' ' . $booking->guest_surname }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-semibold text-gray-900">{{ $booking->user ? $booking->user->email : $booking->guest_email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Booked On</p>
                            <p class="font-semibold text-gray-900">{{ $booking->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Booking Reference</p>
                            <p class="font-mono font-bold text-gray-900">{{ $booking->booking_reference }}</p>
                        </div>
                    </div>
                    @if($booking->accessibility_notes)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-500">Accessibility Notes</p>
                            <p class="text-gray-900 mt-1">{{ $booking->accessibility_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ticket Summary Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-5 border-t-4" style="border-color: #247a7c;">
                    <div class="text-3xl font-bold text-gray-900">{{ $booking->total_tickets }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Tickets</div>
                </div>
                @if($booking->adult_tickets > 0)
                <div class="bg-white rounded-lg shadow-sm p-5 border-t-4 border-blue-500">
                    <div class="text-3xl font-bold text-gray-900">{{ $booking->adult_tickets }}</div>
                    <div class="text-sm text-gray-600 mt-1">Adult</div>
                </div>
                @endif
                @if($booking->child_tickets > 0)
                <div class="bg-white rounded-lg shadow-sm p-5 border-t-4 border-green-500">
                    <div class="text-3xl font-bold text-gray-900">{{ $booking->child_tickets }}</div>
                    <div class="text-sm text-gray-600 mt-1">Child</div>
                </div>
                @endif
                @if($booking->concession_tickets > 0)
                <div class="bg-white rounded-lg shadow-sm p-5 border-t-4" style="border-color: #ae8800;">
                    <div class="text-3xl font-bold text-gray-900">{{ $booking->concession_tickets }}</div>
                    <div class="text-sm text-gray-600 mt-1">Concession</div>
                </div>
                @endif
            </div>

            <!-- Tickets Table -->
            @if($booking->tickets->count() > 0)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4" style="background-color: #ae8800;">
                    <h2 class="text-xl font-bold text-white">🎟️ Individual Tickets</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr style="background-color: #f8f8f8;">
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Reference</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($booking->tickets as $ticket)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-mono text-sm font-semibold text-gray-900">{{ $ticket->ticket_reference }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($ticket->type === 'adult')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Adult</span>
                                    @elseif($ticket->type === 'child')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Child</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white" style="background-color: #ae8800;">Concession</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-semibold text-gray-900">£{{ number_format($ticket->price, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($ticket->status === 'valid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✓ Valid</span>
                                    @elseif($ticket->status === 'used')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Used</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelled</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Total / Payment Info -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Amount</p>
                            <p class="text-3xl font-bold text-gray-900">£{{ number_format($booking->total_amount, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Status</p>
                            @if($booking->status === 'confirmed')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">✅ Confirmed</span>
                            @elseif($booking->status === 'cancelled')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-800">❌ Cancelled</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800">⏳ {{ ucfirst($booking->status) }}</span>
                            @endif
                        </div>
                    </div>
                    @if($booking->status === 'cancelled' && $booking->refund_amount > 0)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Refund Amount</span>
                                <span class="font-bold text-green-700">£{{ number_format($booking->refund_amount, 2) }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cancel Action -->
            @if($booking->status === 'confirmed' && $booking->can_be_cancelled)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6" x-data="{ showCancel: false }">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">Need to cancel?</p>
                            <p class="text-sm text-gray-500 mt-1">
                                @if($booking->refund_percentage === 100)
                                    Full refund available
                                @elseif($booking->refund_percentage > 0)
                                    {{ $booking->refund_percentage }}% refund available (£{{ number_format($booking->total_amount * $booking->refund_percentage / 100, 2) }})
                                @else
                                    No refund available for this booking
                                @endif
                            </p>
                        </div>
                        <button @click="showCancel = true" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg text-sm transition">
                            Cancel Booking
                        </button>
                    </div>

                    <!-- Cancel Confirmation -->
                    <div x-show="showCancel" x-transition class="mt-4 pt-4 border-t border-gray-200">
                        <form action="{{ route('events.booking.cancel', $booking) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="cancellation_reason" class="block text-sm font-semibold text-gray-700 mb-1">Reason for cancellation (optional)</label>
                                <textarea name="cancellation_reason" id="cancellation_reason" rows="3" 
                                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm"
                                          placeholder="Please let us know why you're cancelling..."></textarea>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg text-sm transition">
                                    Confirm Cancellation
                                </button>
                                <button type="button" @click="showCancel = false" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg text-sm transition">
                                    Keep Booking
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions Footer -->
            <div class="flex flex-wrap gap-3 justify-center">
                <a href="{{ route('customer.booking.download', $booking) }}" 
                   class="inline-flex items-center px-6 py-3 text-white font-semibold rounded-lg text-sm transition" style="background-color: #247a7c;">
                    ⬇ Download Booking Info
                </a>
                @if($booking->status === 'confirmed')
                <a href="{{ route('calendar.export-ics', $booking) }}" 
                   class="inline-flex items-center px-6 py-3 text-white font-semibold rounded-lg text-sm transition" style="background-color: #ae8800;">
                    📅 Add to Calendar
                </a>
                @endif
                <a href="{{ route('customer.bookings') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg text-sm transition">
                    ← All Bookings
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

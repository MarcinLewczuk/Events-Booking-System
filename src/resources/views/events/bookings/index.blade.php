<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 bg-white rounded-lg shadow-sm p-6 border-l-4 border-primary-600">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">🎫 My Bookings</h1>
                        <p class="text-gray-600 mt-1">View and manage all your event bookings</p>
                    </div>
                    <div class="flex gap-4">
                        <a href="{{ route('calendar.index') }}" class="text-primary-600 hover:text-primary-700 font-semibold text-sm">
                            📅 My Calendar
                        </a>
                        <a href="{{ route('events') }}" class="text-primary-600 hover:text-primary-700 font-semibold text-sm">
                            🎉 Browse Events
                        </a>
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-700 font-semibold text-sm">
                            ← Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-5 border-t-4 border-primary-600">
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Bookings</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5 border-t-4 border-green-500">
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['upcoming'] }}</div>
                    <div class="text-sm text-gray-600 mt-1">Upcoming</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5 border-t-4 border-gray-400">
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['past'] }}</div>
                    <div class="text-sm text-gray-600 mt-1">Past Events</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5 border-t-4 border-red-400">
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['cancelled'] }}</div>
                    <div class="text-sm text-gray-600 mt-1">Cancelled</div>
                </div>
            </div>

            <!-- Filters & Search -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <form method="GET" action="{{ route('customer.bookings') }}" class="flex flex-col md:flex-row gap-4 items-end">
                    <!-- Search -->
                    <div class="flex-1">
                        <label for="search" class="block text-sm font-semibold text-gray-700 mb-1">Search</label>
                        <input type="text" name="search" id="search" value="{{ $search }}" 
                               placeholder="Search by event name or booking reference..."
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                    </div>

                    <!-- Status Filter -->
                    <div class="w-full md:w-48">
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Bookings</option>
                            <option value="upcoming" {{ $status === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="past" {{ $status === 'past' ? 'selected' : '' }}>Past Events</option>
                            <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="w-full md:w-48">
                        <label for="sort" class="block text-sm font-semibold text-gray-700 mb-1">Sort By</label>
                        <select name="sort" id="sort" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            <option value="date_desc" {{ $sort === 'date_desc' ? 'selected' : '' }}>Date (Newest)</option>
                            <option value="date_asc" {{ $sort === 'date_asc' ? 'selected' : '' }}>Date (Oldest)</option>
                            <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <button type="submit" class="px-5 py-2.5 text-white font-semibold rounded-lg text-sm transition" style="background-color: #247a7c;">
                            Filter
                        </button>
                        @if($search || $status !== 'all' || $sort !== 'date_desc')
                            <a href="{{ route('customer.bookings') }}" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg text-sm transition">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Bookings List -->
            @if($bookings->count() > 0)
                <div class="space-y-4">
                    @foreach($bookings as $booking)
                        @php
                            $event = $booking->event;
                            $isPast = $event->start_datetime < now();
                            $isCancelled = $booking->status === 'cancelled';
                        @endphp
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition {{ $isCancelled ? 'opacity-75' : '' }}">
                            <div class="flex flex-col md:flex-row">
                                <!-- Date Badge -->
                                <div class="md:w-28 flex-shrink-0 flex md:flex-col items-center justify-center p-4 text-white text-center" 
                                     style="background-color: {{ $isCancelled ? '#9ca3af' : ($isPast ? '#6b7280' : '#247a7c') }};">
                                    <span class="text-2xl md:text-3xl font-bold mr-2 md:mr-0">{{ $event->start_datetime->format('d') }}</span>
                                    <span class="text-sm font-semibold uppercase">{{ $event->start_datetime->format('M') }}</span>
                                    <span class="text-xs opacity-75 ml-2 md:ml-0">{{ $event->start_datetime->format('Y') }}</span>
                                </div>

                                <!-- Details -->
                                <div class="flex-1 p-5">
                                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3 class="text-lg font-bold text-gray-900">{{ $event->title }}</h3>
                                                @if($isCancelled)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelled</span>
                                                @elseif($isPast)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Past</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Upcoming</span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex flex-wrap gap-x-6 gap-y-1 text-sm text-gray-600">
                                                <span>🕐 {{ $event->start_datetime->format('g:i A') }}@if($event->end_datetime) – {{ $event->end_datetime->format('g:i A') }}@endif</span>
                                                <span>📍 {{ $event->location->name ?? 'TBA' }}</span>
                                                <span>🎫 {{ $booking->total_tickets }} ticket{{ $booking->total_tickets > 1 ? 's' : '' }}</span>
                                                @if($booking->total_amount > 0)
                                                    <span>💷 £{{ number_format($booking->total_amount, 2) }}</span>
                                                @else
                                                    <span>💷 Free</span>
                                                @endif
                                            </div>

                                            <div class="mt-2 text-xs text-gray-500">
                                                Ref: {{ $booking->booking_reference }}
                                                @if($booking->adult_tickets > 0) · {{ $booking->adult_tickets }} Adult @endif
                                                @if($booking->child_tickets > 0) · {{ $booking->child_tickets }} Child @endif
                                                @if($booking->concession_tickets > 0) · {{ $booking->concession_tickets }} Concession @endif
                                            </div>

                                            @if($isCancelled && $booking->cancellation_reason)
                                                <div class="mt-2 text-xs text-red-600">
                                                    Reason: {{ $booking->cancellation_reason }}
                                                </div>
                                            @endif

                                            @if($isCancelled && $booking->refund_amount > 0)
                                                <div class="mt-1 text-xs text-green-600">
                                                    Refund: £{{ number_format($booking->refund_amount, 2) }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex flex-row md:flex-col gap-2 flex-shrink-0">
                                            <a href="{{ route('events.show', $event) }}" 
                                               class="px-4 py-2 text-white text-sm font-semibold rounded-lg text-center transition hover:opacity-90" style="background-color: #247a7c;">
                                                View Event
                                            </a>

                                            @if(!$isCancelled && !$isPast)
                                                <a href="{{ route('calendar.export-ics', $booking) }}" 
                                                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg text-center transition">
                                                    📅 Add to Calendar
                                                </a>
                                            @endif

                                            @if($booking->can_be_cancelled)
                                                <button onclick="document.getElementById('cancel-modal-{{ $booking->id }}').style.display='flex'" 
                                                        class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-sm font-semibold rounded-lg text-center transition">
                                                    Cancel Booking
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cancel Modal -->
                            @if($booking->can_be_cancelled)
                                <div id="cancel-modal-{{ $booking->id }}" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50" style="display: none;">
                                    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2">Cancel Booking</h3>
                                        <p class="text-gray-600 text-sm mb-1">Are you sure you want to cancel your booking for <strong>{{ $event->title }}</strong>?</p>
                                        
                                        @php
                                            $refundPct = $booking->refund_percentage;
                                            $refundAmt = ($booking->total_amount * $refundPct) / 100;
                                        @endphp
                                        @if($booking->total_amount > 0)
                                            <div class="my-3 p-3 rounded-lg {{ $refundPct === 100 ? 'bg-green-50 text-green-800' : 'bg-yellow-50 text-yellow-800' }} text-sm">
                                                <strong>{{ $refundPct }}% refund</strong> – You will receive £{{ number_format($refundAmt, 2) }} back.
                                            </div>
                                        @endif

                                        <form method="POST" action="{{ route('events.booking.cancel', $booking) }}">
                                            @csrf
                                            <div class="mb-4">
                                                <label for="reason-{{ $booking->id }}" class="block text-sm font-medium text-gray-700 mb-1">Reason (optional)</label>
                                                <textarea name="cancellation_reason" id="reason-{{ $booking->id }}" rows="2" 
                                                          class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500"
                                                          placeholder="Let us know why you're cancelling..."></textarea>
                                            </div>
                                            <div class="flex gap-3 justify-end">
                                                <button type="button" 
                                                        onclick="document.getElementById('cancel-modal-{{ $booking->id }}').style.display='none'"
                                                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">
                                                    Keep Booking
                                                </button>
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg text-sm transition">
                                                    Confirm Cancellation
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $bookings->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    @if($search || $status !== 'all')
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Bookings Found</h3>
                        <p class="text-gray-600 mb-4">No bookings match your current filters.</p>
                        <a href="{{ route('customer.bookings') }}" class="inline-block px-6 py-2.5 text-white font-semibold rounded-lg text-sm transition" style="background-color: #247a7c;">
                            Clear Filters
                        </a>
                    @else
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Bookings Yet</h3>
                        <p class="text-gray-600 mb-4">You haven't booked any events yet. Explore what's on!</p>
                        <a href="{{ route('events') }}" class="inline-block px-6 py-2.5 text-white font-semibold rounded-lg text-sm transition" style="background-color: #247a7c;">
                            Browse Events
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

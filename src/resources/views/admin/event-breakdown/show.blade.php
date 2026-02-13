<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 bg-white rounded-lg shadow-sm p-6 border-l-4 border-primary-600">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h1>
                        <p class="text-gray-600 mt-1">{{ $event->start_datetime->format('l, F j, Y \a\t g:i A') }} · {{ $event->location->name ?? 'TBA' }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.events.show', $event) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-sm transition">View Event</a>
                        <a href="{{ route('admin.events.edit', $event) }}" class="px-4 py-2 text-white font-semibold rounded-lg text-sm transition" style="background-color: #247a7c;">Edit Event</a>
                        <a href="{{ route('admin.event-breakdown.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">← Back</a>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-4 border-t-4 border-blue-500">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_bookings'] }}</div>
                    <div class="text-xs text-gray-600 mt-1">Bookings</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 border-t-4 border-green-500">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_attendees'] }}</div>
                    <div class="text-xs text-gray-600 mt-1">Attendees</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 border-t-4 border-primary-600">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['capacity'] }}</div>
                    <div class="text-xs text-gray-600 mt-1">Capacity</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 border-t-4 border-indigo-500">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['remaining'] }}</div>
                    <div class="text-xs text-gray-600 mt-1">Remaining</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 border-t-4" style="border-color: {{ $stats['occupancy_pct'] >= 90 ? '#ef4444' : ($stats['occupancy_pct'] >= 60 ? '#ae8800' : '#247a7c') }};">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['occupancy_pct'] }}%</div>
                    <div class="text-xs text-gray-600 mt-1">Occupancy</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 border-t-4" style="border-color: #ae8800;">
                    <div class="text-2xl font-bold text-gray-900">£{{ number_format($stats['total_revenue'], 2) }}</div>
                    <div class="text-xs text-gray-600 mt-1">Revenue</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 border-t-4 border-red-400">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['cancelled_bookings'] }}</div>
                    <div class="text-xs text-gray-600 mt-1">Cancelled</div>
                </div>
            </div>

            <!-- Averages & Occupancy Gauge Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Average Stats -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Averages</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Tickets per Booking</span>
                            <span class="text-lg font-bold text-gray-900">{{ $stats['avg_tickets_per_booking'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Revenue per Booking</span>
                            <span class="text-lg font-bold text-gray-900">£{{ number_format($stats['avg_revenue_per_booking'], 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Revenue per Attendee</span>
                            <span class="text-lg font-bold text-gray-900">£{{ $stats['total_attendees'] > 0 ? number_format($stats['total_revenue'] / $stats['total_attendees'], 2) : '0.00' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Occupancy Visual -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Capacity Usage</h3>
                    <div class="flex items-center justify-center">
                        <div class="relative w-36 h-36">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                <path d="M18 2.0845a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                                <path d="M18 2.0845a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="{{ $stats['occupancy_pct'] >= 90 ? '#ef4444' : ($stats['occupancy_pct'] >= 60 ? '#f59e0b' : '#247a7c') }}" stroke-width="3" stroke-dasharray="{{ $stats['occupancy_pct'] }}, 100" stroke-linecap="round"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl font-bold text-gray-900">{{ $stats['occupancy_pct'] }}%</span>
                                <span class="text-xs text-gray-500">filled</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-center text-sm text-gray-600">
                        <span class="font-semibold">{{ $stats['capacity'] - $stats['remaining'] }}</span> booked of <span class="font-semibold">{{ $stats['capacity'] }}</span> capacity
                    </div>
                </div>

                <!-- Booking Source -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Booking Source</h3>
                    @php $totalSource = max($bookingSource['registered'] + $bookingSource['guest'], 1); @endphp
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Registered Users</span>
                                <span class="font-semibold">{{ $bookingSource['registered'] }} ({{ round(($bookingSource['registered'] / $totalSource) * 100) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                <div class="h-full rounded-full" style="width: {{ ($bookingSource['registered'] / $totalSource) * 100 }}%; background-color: #247a7c;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Guest Bookings</span>
                                <span class="font-semibold">{{ $bookingSource['guest'] }} ({{ round(($bookingSource['guest'] / $totalSource) * 100) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                <div class="h-full rounded-full bg-amber-500" style="width: {{ ($bookingSource['guest'] / $totalSource) * 100 }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket Breakdown -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Ticket Breakdown</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach(['adult' => ['icon' => '👤', 'label' => 'Adult', 'color' => '#247a7c'], 'child' => ['icon' => '👶', 'label' => 'Child', 'color' => '#ae8800'], 'concession' => ['icon' => '♿', 'label' => 'Concession', 'color' => '#3b82f6']] as $type => $info)
                        @php $data = $ticketBreakdown[$type] ?? null; @endphp
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xl">{{ $info['icon'] }}</span>
                                <span class="font-bold text-gray-900">{{ $info['label'] }} Tickets</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Count:</span>
                                <span class="font-bold" style="color: {{ $info['color'] }};">{{ $data->count ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between text-sm mt-1">
                                <span class="text-gray-600">Revenue:</span>
                                <span class="font-bold text-gray-900">£{{ number_format($data->revenue ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm mt-1">
                                <span class="text-gray-600">Unit Price:</span>
                                <span class="text-gray-600">£{{ number_format($event->{$type . '_price'} ?? 0, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Daily Booking Trend & Peak Hours -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Daily Booking Trend -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Daily Booking Trend</h3>
                    @if($dailyBookings->isNotEmpty())
                        @php $maxDaily = max(1, $dailyBookings->max('count')); @endphp
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($dailyBookings as $day)
                                <div class="flex items-center gap-3">
                                    <div class="w-20 text-xs text-gray-600 font-medium text-right flex-shrink-0">{{ \Carbon\Carbon::parse($day->date)->format('M d') }}</div>
                                    <div class="flex-1 bg-gray-100 rounded-full h-6 overflow-hidden">
                                        <div class="h-full rounded-full flex items-center justify-end pr-2 text-xs font-bold text-white transition-all" 
                                             style="width: {{ max(($day->count / $maxDaily) * 100, 12) }}%; background-color: #247a7c;">
                                            {{ $day->count }}
                                        </div>
                                    </div>
                                    <div class="w-20 text-xs text-gray-500 text-right flex-shrink-0">£{{ number_format($day->revenue, 2) }}</div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between text-xs text-gray-500">
                            <span>{{ $dailyBookings->count() }} booking day(s)</span>
                            <span>{{ $dailyBookings->sum('tickets') }} total tickets</span>
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No booking data available yet.</p>
                    @endif
                </div>

                <!-- Peak Booking Hours + Tags -->
                <div class="space-y-6">
                    <!-- Peak Booking Hours -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Peak Booking Hours</h3>
                        @if($peakHours->isNotEmpty())
                            <div class="space-y-2">
                                @foreach($peakHours as $ph)
                                    @php $maxPeak = max(1, $peakHours->max('count')); @endphp
                                    <div class="flex items-center gap-3">
                                        <div class="w-16 text-xs text-gray-600 font-medium text-right">{{ str_pad($ph->hour, 2, '0', STR_PAD_LEFT) }}:00</div>
                                        <div class="flex-1 bg-gray-100 rounded-full h-5 overflow-hidden">
                                            <div class="h-full rounded-full flex items-center justify-end pr-2 text-xs font-bold text-white" 
                                                 style="width: {{ max(($ph->count / $maxPeak) * 100, 15) }}%; background-color: #ae8800;">
                                                {{ $ph->count }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No booking data available yet.</p>
                        @endif
                    </div>

                    <!-- Event Tags -->
                    @if($event->tags->isNotEmpty())
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Event Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($event->tags as $tag)
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bookings List -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">All Bookings ({{ $bookings->count() }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Reference</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Customer</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">Adult</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">Child</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">Concession</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">Total Tickets</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Amount</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">Status</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Booked At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-gray-50 transition {{ $booking->status === 'cancelled' ? 'opacity-60' : '' }}">
                                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $booking->booking_reference }}</td>
                                    <td class="px-4 py-3">
                                        <span class="font-medium text-gray-900">{{ $booking->attendee_name }}</span>
                                        <br><span class="text-xs text-gray-500">{{ $booking->attendee_email }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">{{ $booking->adult_tickets }}</td>
                                    <td class="px-4 py-3 text-center">{{ $booking->child_tickets }}</td>
                                    <td class="px-4 py-3 text-center">{{ $booking->concession_tickets }}</td>
                                    <td class="px-4 py-3 text-center font-bold">{{ $booking->total_tickets }}</td>
                                    <td class="px-4 py-3 text-right font-medium">£{{ number_format($booking->total_amount, 2) }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @if($booking->status === 'confirmed')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Confirmed</span>
                                        @elseif($booking->status === 'cancelled')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelled</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($booking->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $booking->created_at->format('M d, Y g:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-8 text-center text-gray-500">No bookings for this event.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

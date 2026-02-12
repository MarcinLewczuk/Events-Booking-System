<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 bg-white rounded-lg shadow-sm p-6 border-l-4 border-secondary-600">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">🎉 Browse All Events</h1>
                        <p class="text-gray-600 mt-1">Explore upcoming events at Delapré Abbey</p>
                    </div>
                    <div class="flex gap-4">
                        @auth
                            @if(auth()->user()->role === 'customer')
                                <a href="{{ route('calendar.index') }}" class="text-primary-600 hover:text-primary-700 font-semibold">
                                    My Calendar →
                                </a>
                            @endif
                        @endauth
                        <a href="{{ route('events') }}" class="text-gray-600 hover:text-gray-700 font-semibold">
                            ← Back to Events
                        </a>
                    </div>
                </div>
            </div>

            <!-- Calendar Container -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Controls -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ $currentDate->format('F Y') }}
                        </h2>
                        <div class="flex gap-2">
                            <a href="{{ route('calendar.browse-all', ['month' => $currentDate->copy()->subMonth()->month, 'year' => $currentDate->copy()->subMonth()->year]) }}" 
                               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg transition">
                                ← Prev
                            </a>
                            <a href="{{ route('calendar.browse-all', ['month' => now()->month, 'year' => now()->year]) }}" 
                               class="px-4 py-2 text-white font-semibold rounded-lg transition" style="background-color: #ae8800;">
                                Today
                            </a>
                            <a href="{{ route('calendar.browse-all', ['month' => $currentDate->copy()->addMonth()->month, 'year' => $currentDate->copy()->addMonth()->year]) }}" 
                               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg transition">
                                Next →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Calendar Table -->
                <table class="w-full border-collapse">
                    <thead>
                        <tr style="background-color: #ae8800;">
                            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                                <th class="p-3 text-center font-bold text-white text-sm border border-gray-300" style="width: 14.28%;">
                                    {{ $dayName }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $startDate = $currentDate->copy()->startOfMonth()->startOfWeek(\Carbon\Carbon::SUNDAY);
                            $endDate = $currentDate->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SATURDAY);
                            $currentIterDate = $startDate->copy();
                        @endphp

                        @while($currentIterDate <= $endDate)
                            <tr>
                                @for($i = 0; $i < 7; $i++)
                                    @php
                                        $isCurrentMonth = $currentIterDate->month === $currentDate->month;
                                        $isToday = $currentIterDate->format('Y-m-d') === now()->format('Y-m-d');
                                        $dayEvents = $allEvents->filter(function($event) use ($currentIterDate) {
                                            return $event->start_datetime->format('Y-m-d') === $currentIterDate->format('Y-m-d');
                                        })->values();
                                    @endphp
                                    <td class="border border-gray-200 p-2 align-top {{ $isCurrentMonth ? 'bg-white' : 'bg-gray-50' }}" style="height: 110px; {{ $isToday ? 'background-color: #fef9e6; outline: 2px solid #ae8800; outline-offset: -2px;' : '' }}">
                                        <div class="mb-1">
                                            @if($isToday)
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold text-white" style="background-color: #ae8800;">{{ $currentIterDate->day }}</span>
                                            @else
                                                <span class="text-sm font-bold {{ $isCurrentMonth ? 'text-gray-900' : 'text-gray-400' }}">{{ $currentIterDate->day }}</span>
                                            @endif
                                        </div>
                                        @foreach($dayEvents as $event)
                                            @php
                                                $isBooked = in_array($event->id, $bookedEventIds);
                                            @endphp
                                            <a href="{{ route('events.show', $event) }}" 
                                               class="block text-xs text-white px-1.5 py-0.5 rounded truncate font-medium mb-1 hover:opacity-80 transition"
                                               style="background-color: {{ $isBooked ? '#247a7c' : '#ae8800' }};"
                                               title="{{ $event->title }} - {{ $event->start_datetime->format('g:i A') }}{{ $isBooked ? ' (Booked)' : '' }}">
                                                {{ $isBooked ? '✓ ' : '' }}{{ $event->title }}
                                            </a>
                                        @endforeach
                                    </td>
                                    @php $currentIterDate->addDay(); @endphp
                                @endfor
                            </tr>
                        @endwhile
                    </tbody>
                </table>

                <!-- Legend -->
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center gap-6 flex-wrap text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-3 rounded" style="background-color: #ae8800;"></div>
                            <span class="text-gray-700">Available Events</span>
                        </div>
                        @auth
                            @if(auth()->user()->role === 'customer')
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-3 rounded" style="background-color: #247a7c;"></div>
                                    <span class="text-gray-700">Your Bookings</span>
                                </div>
                            @endif
                        @endauth
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-white text-xs font-bold" style="background-color: #ae8800;">12</span>
                            <span class="text-gray-700">Today</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Events Listing -->
            @if($allEvents->count() > 0)
                <div class="mt-8 bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900">Events This Month</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Event</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Date</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Time</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Location</th>
                                    <th class="px-6 py-3 text-center font-semibold text-gray-900">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($allEvents->sortBy(function($e) { return $e->start_datetime; }) as $event)
                                    @php
                                        $isBooked = in_array($event->id, $bookedEventIds);
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition cursor-pointer" onclick="window.location.href='{{ route('events.show', $event) }}'">
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-gray-900">{{ $event->title }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $event->start_datetime->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $event->start_datetime->format('g:i A') }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $event->location->name ?? 'TBA' }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($isBooked)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white" style="background-color: #247a7c;">
                                                    ✓ Booked
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Available
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="mt-8 bg-white rounded-lg shadow-sm p-8 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Events</h3>
                    <p class="text-gray-600">No events scheduled for this month.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

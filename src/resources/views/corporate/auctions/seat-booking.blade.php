<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Select Your Seat</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ $auction->title }} - {{ $auction->auction_date->format('F d, Y') }}</p>
                    </div>
                    <a href="{{ route('auctions.show', $auction) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        ← Back to Auction
                    </a>
                </div>
            </div>

            <!-- Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            @foreach($errors->all() as $error)
                                <p class="text-sm font-medium text-red-800">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Seating Map -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <!-- Stage -->
                        <div class="mb-8">
                            <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white text-center py-4 rounded-lg shadow-md">
                                <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                                </svg>
                                <p class="font-semibold text-lg">STAGE / PODIUM</p>
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="mb-6 flex flex-wrap gap-4 justify-center text-sm">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-500 rounded border-2 border-green-600 mr-2"></div>
                                <span class="text-gray-700">Available</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-red-500 rounded border-2 border-red-600 mr-2"></div>
                                <span class="text-gray-700">Booked</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-500 rounded border-2 border-purple-600 mr-2"></div>
                                <span class="text-gray-700">Your Seat</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-400 rounded border-2 border-gray-500 mr-2"></div>
                                <span class="text-gray-700">Unavailable</span>
                            </div>
                        </div>

                        <!-- Seat Grid -->
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full">
                                @foreach($seats as $row => $rowSeats)
                                    <div class="flex items-center mb-2">
                                        <!-- Row Label -->
                                        <div class="w-8 text-center font-bold text-gray-700 mr-2">{{ $row }}</div>
                                        
                                        <!-- Seats -->
                                        <div class="flex gap-2">
                                            @foreach($rowSeats as $seat)
                                                @php
                                                    $bgColor = match($seat['status']) {
                                                        'available' => 'bg-green-500 hover:bg-green-600 cursor-pointer',
                                                        'booked' => 'bg-red-500 cursor-not-allowed',
                                                        'yours' => 'bg-purple-500 cursor-default',
                                                        'disabled' => 'bg-gray-400 cursor-not-allowed',
                                                        default => 'bg-gray-300'
                                                    };
                                                    $borderColor = match($seat['status']) {
                                                        'available' => 'border-green-600',
                                                        'booked' => 'border-red-600',
                                                        'yours' => 'border-purple-600',
                                                        'disabled' => 'border-gray-500',
                                                        default => 'border-gray-400'
                                                    };
                                                @endphp
                                                
                                                @if($seat['status'] === 'available')
                                                    <button
                                                        type="button"
                                                        onclick="selectSeat('{{ $seat['number'] }}')"
                                                        class="seat-button w-10 h-10 {{ $bgColor }} {{ $borderColor }} border-2 rounded text-white text-xs font-semibold transition-all duration-200 hover:scale-110 hover:shadow-lg"
                                                        data-seat="{{ $seat['number'] }}"
                                                        title="Seat {{ $seat['number'] }} - Click to book"
                                                    >
                                                        {{ substr($seat['number'], 1) }}
                                                    </button>
                                                @else
                                                    <div
                                                        class="w-10 h-10 {{ $bgColor }} {{ $borderColor }} border-2 rounded text-white text-xs font-semibold flex items-center justify-center"
                                                        title="Seat {{ $seat['number'] }} - {{ ucfirst($seat['status']) }}"
                                                    >
                                                        {{ substr($seat['number'], 1) }}
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-6 text-center text-sm text-gray-600">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Click on a green seat to book it
                        </div>
                    </div>
                </div>

                <!-- Booking Info -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Booking Information</h3>
                        
                        @if($existingBooking)
                            <!-- Current Booking -->
                            <div class="mb-6 p-4 bg-purple-50 border-2 border-purple-500 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <h4 class="font-bold text-purple-900">Your Current Seat</h4>
                                </div>
                                <p class="text-3xl font-bold text-purple-600 mb-3">{{ $existingBooking->seat_number }}</p>
                                <p class="text-xs text-gray-600 mb-4">Booked on {{ $existingBooking->created_at->format('M d, Y') }}</p>
                                
                                <form method="POST" action="{{ route('customer.seat-booking.cancel', $auction) }}" 
                                      onsubmit="return confirm('Are you sure you want to cancel your seat booking?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                        Cancel Booking
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- No Booking Yet -->
                            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm text-blue-800">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    You haven't booked a seat yet. Select a green seat from the map to reserve your spot.
                                </p>
                            </div>
                        @endif

                        <!-- Auction Details -->
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Location:</span>
                                <span class="font-semibold text-gray-900">{{ $auction->location->name }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Date:</span>
                                <span class="font-semibold text-gray-900">{{ $auction->auction_date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Time:</span>
                                <span class="font-semibold text-gray-900">{{ $auction->auction_date->format('g:i A') }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Total Seats:</span>
                                <span class="font-semibold text-gray-900">{{ $location->seating_rows * $location->seating_columns }}</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">Available:</span>
                                <span class="font-semibold text-green-600">{{ $auction->getAvailableSeatsCount() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Form for Booking -->
    <form id="bookingForm" method="POST" action="{{ route('customer.seat-booking.book', $auction) }}" style="display: none;">
        @csrf
        <input type="hidden" name="seat_number" id="selectedSeat">
    </form>

    <script>
        function selectSeat(seatNumber) {
            if (confirm('Do you want to book seat ' + seatNumber + '?')) {
                document.getElementById('selectedSeat').value = seatNumber;
                document.getElementById('bookingForm').submit();
            }
        }
    </script>
</x-app-layout>
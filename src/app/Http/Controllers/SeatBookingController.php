<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\SeatBooking;
use Illuminate\Http\Request;

class SeatBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'role:customer']);
    }

    /**
     * Show seat selection page for auction
     */
    public function show(Auction $auction)
    {
        // Check if user is an approved buyer
        if (!auth()->user()->buyer_approved_status) {
            return redirect()->route('home')->withErrors(['error' => 'You must be an approved buyer to book seats.']);
        }

        // Check if auction has a location with seating
        if (!$auction->location || !$auction->location->seating_rows) {
            return back()->withErrors(['error' => 'This auction does not have seating available.']);
        }

        // Check if auction is in the future
        if ($auction->auction_date < now()) {
            return back()->withErrors(['error' => 'You cannot book seats for past auctions.']);
        }

        // Check if user already has a booking
        $existingBooking = SeatBooking::where('auction_id', $auction->id)
            ->where('user_id', auth()->id())
            ->where('status', '!=', 'cancelled')
            ->first();

        // Get all seats and their status
        $location = $auction->location;
        
        // Get all active bookings for this auction
        $bookedSeats = SeatBooking::where('auction_id', $auction->id)
            ->where('status', '!=', 'cancelled')
            ->pluck('seat_number')
            ->toArray();
        
        $seats = [];
        $rows = range('A', chr(ord('A') + $location->seating_rows - 1));
        
        foreach ($rows as $row) {
            $seats[$row] = [];
            for ($col = 1; $col <= $location->seating_columns; $col++) {
                $seatNumber = $row . $col;
                $status = 'available';
                
                // Check if disabled
                if (in_array($seatNumber, $location->disabled_seats ?? [])) {
                    $status = 'disabled';
                }
                // Check if booked
                else if (in_array($seatNumber, $bookedSeats)) {
                    // Check if it's the current user's booking
                    if ($existingBooking && $existingBooking->seat_number === $seatNumber) {
                        $status = 'yours';
                    } else {
                        $status = 'booked';
                    }
                }
                
                $seats[$row][] = [
                    'number' => $seatNumber,
                    'status' => $status
                ];
            }
        }

        return view('corporate.auctions.seat-booking', compact('auction', 'seats', 'existingBooking', 'location'));
    }

    /**
     * Book a seat
     */
    public function book(Request $request, Auction $auction)
    {
        // Check if user is an approved buyer
        if (!auth()->user()->buyer_approved_status) {
            return redirect()->route('home')->withErrors(['error' => 'You must be an approved buyer to book seats.']);
        }

        // Check if auction is in the future
        if ($auction->auction_date < now()) {
            return back()->withErrors(['error' => 'You cannot book seats for past auctions.']);
        }

        $request->validate([
            'seat_number' => 'required|string',
        ]);

        // Check if user already has an active booking for this auction
        $existingBooking = SeatBooking::where('auction_id', $auction->id)
            ->where('user_id', auth()->id())
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingBooking) {
            return back()->withErrors(['error' => 'You have already booked seat ' . $existingBooking->seat_number . ' for this auction. Please cancel it first if you want to book a different seat.']);
        }

        // Check if seat is already booked by someone else
        $seatTaken = SeatBooking::where('auction_id', $auction->id)
            ->where('seat_number', $request->seat_number)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($seatTaken) {
            return back()->withErrors(['error' => 'This seat has already been booked by another customer.']);
        }

        // Check if seat is disabled
        if (in_array($request->seat_number, $auction->location->disabled_seats ?? [])) {
            return back()->withErrors(['error' => 'This seat is not available for booking.']);
        }

        // Validate seat number format and existence
        $location = $auction->location;
        $row = substr($request->seat_number, 0, 1);
        $col = (int)substr($request->seat_number, 1);
        
        $maxRow = chr(ord('A') + $location->seating_rows - 1);
        if ($row < 'A' || $row > $maxRow || $col < 1 || $col > $location->seating_columns) {
            return back()->withErrors(['error' => 'Invalid seat number.']);
        }

        // Create booking
        try {
            SeatBooking::create([
                'auction_id' => $auction->id,
                'user_id' => auth()->id(),
                'seat_number' => $request->seat_number,
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            return redirect()->route('customer.seat-booking.show', $auction)
                ->with('success', 'Seat ' . $request->seat_number . ' has been successfully booked!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unable to book this seat. Please try again or choose a different seat.']);
        }
    }

    /**
     * Cancel booking
     */
    public function cancel(Auction $auction)
    {
        // Check if user is an approved buyer
        if (!auth()->user()->buyer_approved_status) {
            return redirect()->route('home')->withErrors(['error' => 'You must be an approved buyer to manage bookings.']);
        }

        // Find the user's active booking
        $booking = SeatBooking::where('auction_id', $auction->id)
            ->where('user_id', auth()->id())
            ->where('status', '!=', 'cancelled')
            ->first();

        if (!$booking) {
            return back()->withErrors(['error' => 'You do not have an active booking for this auction.']);
        }

        // Delete the booking record
        $booking->delete();

        return redirect()->route('customer.seat-booking.show', $auction)
            ->with('success', 'Your seat booking has been cancelled. You can now book a different seat.');
    }
}
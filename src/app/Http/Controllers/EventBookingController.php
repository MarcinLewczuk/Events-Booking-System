<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EventBookingController extends Controller
{
    /**
     * Show the booking page for an event
     */
    public function show($id)
    {
        $event = Event::with('location')->findOrFail($id);

        // Check if event is cancelled
        if ($event->is_cancelled) {
            return view('events.booking.cancelled', compact('event'));
        }

        // Check if user already has a booking (prevent duplicates)
        $existingBooking = null;
        if (Auth::check()) {
            $existingBooking = $event->bookings()
                ->where('user_id', Auth::id())
                ->where('status', 'confirmed')
                ->first();
        }

        // Pre-fill user data if authenticated
        $userData = null;
        if (Auth::check()) {
            $userData = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ];
        }

        return view('events.booking.show', compact('event', 'existingBooking', 'userData'));
    }

    /**
     * Process the booking submission
     */
    public function store(Request $request, $id)
    {
        $event = Event::with('location')->findOrFail($id);

        // Validation rules
        $rules = [
            'adult_tickets' => 'required|integer|min:0',
            'child_tickets' => 'required|integer|min:0',
            'concession_tickets' => 'required|integer|min:0',
            'accessibility_notes' => 'nullable|string|max:1000',
            'newsletter_opt_in' => 'boolean',
            'g-recaptcha-response' => 'required',
        ];

        $messages = [
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
        ];

        // Paid events require authentication
        if ($event->is_paid && !Auth::check()) {
            return redirect()->route('login')
                ->with('intended_url', route('events.book', $event->id))
                ->with('error', 'Please login or create an account to book paid events.');
        }

        // For free events, guest bookings require name and email
        if (!Auth::check()) {
            $rules['guest_name'] = 'required|string|max:255';
            $rules['guest_email'] = 'required|email|max:255';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify reCAPTCHA response
        $recaptchaResponse = $request->input('g-recaptcha-response');
        if (!$this->verifyRecaptcha($recaptchaResponse)) {
            return redirect()->back()
                ->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.'])
                ->withInput();
        }

        $validated = $validator->validated();

        // Calculate total tickets
        $totalTickets = $validated['adult_tickets'] + 
                       $validated['child_tickets'] + 
                       $validated['concession_tickets'];

        // Business rules validation
        $errors = [];

        // Must book at least 1 ticket
        if ($totalTickets === 0) {
            $errors[] = 'Please select at least one ticket.';
        }

        // Maximum 4 tickets per booking
        if ($totalTickets > 4) {
            $errors[] = 'Maximum 4 tickets per booking. Please make a separate booking for larger groups.';
        }

        // Check event is not cancelled
        if ($event->status === 'cancelled') {
            $errors[] = 'This event has been cancelled.';
        }

        // Check capacity
        if (!$event->canAccommodate($totalTickets)) {
            $remainingSpaces = $event->remaining_spaces;
            if ($remainingSpaces === 0) {
                $errors[] = 'This event is fully booked.';
            } else {
                $errors[] = "Only {$remainingSpaces} space(s) remaining. Please reduce your ticket quantity.";
            }
        }

        // Check for duplicate booking
        $userIdOrEmail = Auth::check() ? Auth::id() : $validated['guest_email'] ?? null;
        if ($userIdOrEmail && $event->hasBooking($userIdOrEmail)) {
            $errors[] = 'You have already booked this event.';
        }

        if (!empty($errors)) {
            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        }

        // Calculate total amount
        $totalAmount = ($validated['adult_tickets'] * $event->adult_price) +
                      ($validated['child_tickets'] * $event->child_price) +
                      ($validated['concession_tickets'] * $event->concession_price);

        // Create booking in transaction to handle race conditions
        try {
            DB::beginTransaction();

            // Re-check capacity within transaction (race condition protection)
            $event->refresh();
            if (!$event->canAccommodate($totalTickets)) {
                DB::rollBack();
                return redirect()->back()
                    ->withErrors(['Just missed it! Someone else booked the last available spots. Please try again with fewer tickets.'])
                    ->withInput();
            }

            $booking = EventBooking::create([
                'event_id' => $event->id,
                'user_id' => Auth::id(),
                'guest_name' => $validated['guest_name'] ?? null,
                'guest_email' => $validated['guest_email'] ?? null,
                'adult_tickets' => $validated['adult_tickets'],
                'child_tickets' => $validated['child_tickets'],
                'concession_tickets' => $validated['concession_tickets'],
                'total_tickets' => $totalTickets,
                'total_amount' => $totalAmount,
                'accessibility_notes' => $validated['accessibility_notes'] ?? null,
                'newsletter_opt_in' => $request->boolean('newsletter_opt_in'),
                'status' => 'confirmed',
            ]);

            DB::commit();

            // TODO: Queue confirmation email
            // dispatch(new SendBookingConfirmationEmail($booking));

            // TODO: Handle payment for paid events
            // if ($event->is_paid) {
            //     return redirect()->route('events.payment', $booking->id);
            // }

            return redirect()->route('events.booking.confirmation', $booking->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['An error occurred while processing your booking. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Show booking confirmation page
     */
    public function confirmation($bookingId)
    {
        $booking = EventBooking::with(['event.location', 'user'])->findOrFail($bookingId);

        // Only allow viewing own bookings
        if (Auth::check()) {
            if ($booking->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to booking.');
            }
        }

        return view('events.booking.confirmation', compact('booking'));
    }

    /**
     * Show manage booking page
     */
    public function manage($bookingId)
    {
        $booking = EventBooking::with(['event.location', 'user'])->findOrFail($bookingId);

        // Only allow viewing own bookings
        if (Auth::check()) {
            if ($booking->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to booking.');
            }
        } else {
            // For guest bookings, could implement email verification link
            abort(403, 'Please login to manage your booking.');
        }

        return view('events.booking.manage', compact('booking'));
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, $bookingId)
    {
        $booking = EventBooking::with(['event'])->findOrFail($bookingId);

        // Authorization check
        if (Auth::check()) {
            if ($booking->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to booking.');
            }
        } else {
            abort(403, 'Please login to cancel your booking.');
        }

        // Check if cancellation is allowed
        if (!$booking->can_be_cancelled) {
            return redirect()->back()
                ->withErrors(['This booking can no longer be cancelled.']);
        }

        try {
            $refundAmount = $booking->cancel($request->input('reason'));

            // TODO: Queue cancellation email
            // dispatch(new SendCancellationEmail($booking));

            $message = 'Your booking has been cancelled successfully.';
            if ($refundAmount > 0) {
                $message .= " A refund of £{$refundAmount} will be processed within 5-7 business days.";
            }

            return redirect()->route('events.booking.manage', $booking->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors([$e->getMessage()]);
        }
    }

    /**
     * Generate calendar download links
     */
    public function addToCalendar($bookingId, $type = 'google')
    {
        $booking = EventBooking::with(['event.location'])->findOrFail($bookingId);
        $event = $booking->event;

        if ($type === 'google') {
            $url = 'https://calendar.google.com/calendar/render?action=TEMPLATE';
            $url .= '&text=' . urlencode($event->title);
            $url .= '&dates=' . $event->start_datetime->format('Ymd\THis\Z');
            $url .= '/' . $event->end_datetime->format('Ymd\THis\Z');
            $url .= '&details=' . urlencode($event->description);
            $url .= '&location=' . urlencode($event->location->address);

            return redirect($url);
        }

        // iCal format
        if ($type === 'ical') {
            $ics = "BEGIN:VCALENDAR\r\n";
            $ics .= "VERSION:2.0\r\n";
            $ics .= "PRODID:-//Delapré Abbey//Event Booking//EN\r\n";
            $ics .= "BEGIN:VEVENT\r\n";
            $ics .= "UID:" . $booking->booking_reference . "@delapreabbey.org\r\n";
            $ics .= "DTSTAMP:" . now()->format('Ymd\THis\Z') . "\r\n";
            $ics .= "DTSTART:" . $event->start_datetime->format('Ymd\THis\Z') . "\r\n";
            $ics .= "DTEND:" . $event->end_datetime->format('Ymd\THis\Z') . "\r\n";
            $ics .= "SUMMARY:" . $event->title . "\r\n";
            $ics .= "DESCRIPTION:" . str_replace("\n", "\\n", $event->description) . "\r\n";
            $ics .= "LOCATION:" . $event->location->address . "\r\n";
            $ics .= "END:VEVENT\r\n";
            $ics .= "END:VCALENDAR\r\n";

            return response($ics)
                ->header('Content-Type', 'text/calendar; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="event-' . $booking->booking_reference . '.ics"');
        }

        abort(404);
    }

    /**
     * Verify reCAPTCHA response with Google's API
     */
    private function verifyRecaptcha($response)
    {
        if (empty($response)) {
            return false;
        }

        $secret = config('services.recaptcha.secret_key');
        if (empty($secret)) {
            // If secret key is not configured, log warning but allow through
            Log::warning('reCAPTCHA secret key not configured');
            return true;
        }

        try {
            $verifyResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $response,
                'remoteip' => request()->ip()
            ]);
            
            $result = $verifyResponse->json();
            
            return isset($result['success']) && $result['success'] === true;
        } catch (\Exception $e) {
            // Log the error but don't block the booking
            Log::error('reCAPTCHA verification error: ' . $e->getMessage());
            return true; // Fail open to prevent blocking legitimate users
        }
    }
}

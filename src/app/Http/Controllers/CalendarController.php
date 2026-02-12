<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Display calendar page with all user bookings
     */
    public function index()
    {
        $user = auth()->user();
        
        $bookings = EventBooking::where('user_id', $user->id)
            ->with(['event.location'])
            ->where('status', 'confirmed')
            ->orderBy('event_id')
            ->get();

        $month = request('month', now()->month);
        $year = request('year', now()->year);
        
        $currentDate = Carbon::createFromDate($year, $month, 1);
        
        // Get events for calendar display
        $events = $bookings->filter(function($booking) use ($currentDate) {
            return $booking->event->start_datetime->format('Y-m') === $currentDate->format('Y-m');
        });

        return view('events.calendar.index', compact('bookings', 'events', 'currentDate'));
    }

    /**
     * Display all user bookings with filtering and search
     */
    public function myBookings(Request $request)
    {
        $user = auth()->user();
        $query = EventBooking::where('user_id', $user->id)
            ->with(['event.location']);

        // Filter by status
        $status = $request->get('status', 'all');
        if ($status === 'upcoming') {
            $query->where('status', 'confirmed')
                  ->whereHas('event', fn($q) => $q->where('start_datetime', '>=', now()));
        } elseif ($status === 'past') {
            $query->where('status', 'confirmed')
                  ->whereHas('event', fn($q) => $q->where('start_datetime', '<', now()));
        } elseif ($status === 'cancelled') {
            $query->where('status', 'cancelled');
        } elseif ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search by event name or booking reference
        $search = $request->get('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhereHas('event', fn($eq) => $eq->where('title', 'like', "%{$search}%"));
            });
        }

        // Sort
        $sort = $request->get('sort', 'date_desc');
        switch ($sort) {
            case 'date_asc':
                $query->join('events', 'event_bookings.event_id', '=', 'events.id')
                      ->orderBy('events.start_datetime', 'asc')
                      ->select('event_bookings.*');
                break;
            case 'date_desc':
                $query->join('events', 'event_bookings.event_id', '=', 'events.id')
                      ->orderBy('events.start_datetime', 'desc')
                      ->select('event_bookings.*');
                break;
            case 'name_asc':
                $query->join('events', 'event_bookings.event_id', '=', 'events.id')
                      ->orderBy('events.title', 'asc')
                      ->select('event_bookings.*');
                break;
            default:
                $query->latest();
        }

        $bookings = $query->paginate(12)->withQueryString();

        // Stats
        $stats = [
            'total' => EventBooking::where('user_id', $user->id)->count(),
            'upcoming' => EventBooking::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->whereHas('event', fn($q) => $q->where('start_datetime', '>=', now()))
                ->count(),
            'past' => EventBooking::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->whereHas('event', fn($q) => $q->where('start_datetime', '<', now()))
                ->count(),
            'cancelled' => EventBooking::where('user_id', $user->id)
                ->where('status', 'cancelled')
                ->count(),
        ];

        return view('events.bookings.index', compact('bookings', 'status', 'search', 'sort', 'stats'));
    }

    /**
     * Browse all available events in calendar format
     */
    public function browseAll()

    {
        $month = request('month', now()->month);
        $year = request('year', now()->year);
        
        $currentDate = Carbon::createFromDate($year, $month, 1);
        
        // Get all active events for the month
        $allEvents = Event::where('status', 'active')
            ->where('start_datetime', '>=', $currentDate->copy()->startOfMonth())
            ->where('start_datetime', '<=', $currentDate->copy()->endOfMonth()->endOfDay())
            ->with(['location', 'tags'])
            ->orderBy('start_datetime')
            ->get();

        // Get user's booked event IDs if logged in
        $bookedEventIds = auth()->check() 
            ? EventBooking::where('user_id', auth()->id())
                ->where('status', 'confirmed')
                ->pluck('event_id')
                ->toArray()
            : [];

        return view('events.calendar.browse-all', compact('allEvents', 'currentDate', 'bookedEventIds'));
    }

    /**
     * Export booking as iCalendar format for Windows Calendar
     */
    public function exportIcs($bookingId)
    {
        $booking = EventBooking::findOrFail($bookingId);
        $event = $booking->event;

        // Ensure user owns this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Create iCalendar format
        $ics = "BEGIN:VCALENDAR\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "PRODID:-//Delapré Abbey Events//Event Booking Calendar//EN\r\n";
        $ics .= "CALSCALE:GREGORIAN\r\n";
        $ics .= "METHOD:PUBLISH\r\n";
        $ics .= "X-WR-CALNAME:Delapré Abbey Events\r\n";
        $ics .= "X-WR-TIMEZONE:UTC\r\n";
        
        $ics .= "BEGIN:VEVENT\r\n";
        $ics .= "UID:" . $booking->id . "@delapre.com\r\n";
        $ics .= "DTSTAMP:" . now()->format('Ymd\THis\Z') . "\r\n";
        $ics .= "DTSTART:" . $event->start_datetime->format('Ymd\THis\Z') . "\r\n";
        
        if ($event->end_datetime) {
            $ics .= "DTEND:" . $event->end_datetime->format('Ymd\THis\Z') . "\r\n";
        } else {
            $ics .= "DTEND:" . $event->start_datetime->addHours(2)->format('Ymd\THis\Z') . "\r\n";
        }
        
        $ics .= "SUMMARY:" . addslashes($event->title) . "\r\n";
        $ics .= "DESCRIPTION:" . addslashes($event->description ?? '') . "\r\n";
        
        if ($event->location) {
            $ics .= "LOCATION:" . addslashes($event->location->name . ", " . $event->location->address . ", " . $event->location->city) . "\r\n";
        }
        
        $ics .= "URL:" . route('events.show', $event->id) . "\r\n";
        $ics .= "ORGANIZER;CN=Delapré Abbey Events:mailto:events@delapre.com\r\n";
        $ics .= "ATTENDEE;PARTSTAT=ACCEPTED;ROLE=REQ-PARTICIPANT;CN=" . auth()->user()->first_name . " " . auth()->user()->last_name . ":mailto:" . auth()->user()->email . "\r\n";
        $ics .= "STATUS:CONFIRMED\r\n";
        $ics .= "SEQUENCE:0\r\n";
        $ics .= "END:VEVENT\r\n";
        $ics .= "END:VCALENDAR\r\n";

        return response($ics)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $event->title . '.ics"');
    }
}

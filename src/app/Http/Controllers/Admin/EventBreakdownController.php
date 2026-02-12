<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventBooking;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventBreakdownController extends Controller
{
    /**
     * Show event breakdown dashboard for admins
     */
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);

        // Monthly event counts for the year
        $monthlyEvents = Event::selectRaw('MONTH(start_datetime) as month, COUNT(*) as count')
            ->whereYear('start_datetime', $year)
            ->where('status', '!=', 'draft')
            ->groupByRaw('MONTH(start_datetime)')
            ->orderByRaw('MONTH(start_datetime)')
            ->pluck('count', 'month')
            ->toArray();

        // Fill all 12 months
        $monthlyCounts = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyCounts[$m] = $monthlyEvents[$m] ?? 0;
        }

        // All events with booking stats
        $statusFilter = $request->get('status', 'all');
        $search = $request->get('search');
        $monthFilter = $request->get('month');

        $eventsQuery = Event::withCount(['confirmedBookings'])
            ->withSum('confirmedBookings', 'total_tickets')
            ->withSum('confirmedBookings', 'total_amount')
            ->with('location');

        if ($statusFilter !== 'all') {
            $eventsQuery->where('status', $statusFilter);
        }

        if ($search) {
            $eventsQuery->where('title', 'like', "%{$search}%");
        }

        if ($monthFilter) {
            $eventsQuery->whereMonth('start_datetime', $monthFilter)
                        ->whereYear('start_datetime', $year);
        }

        $events = $eventsQuery->orderBy('start_datetime', 'desc')->paginate(15)->withQueryString();

        // Overall stats
        $stats = [
            'total_events' => Event::whereYear('start_datetime', $year)->where('status', '!=', 'draft')->count(),
            'total_bookings' => EventBooking::whereHas('event', fn($q) => $q->whereYear('start_datetime', $year))->where('status', 'confirmed')->count(),
            'total_attendees' => EventBooking::whereHas('event', fn($q) => $q->whereYear('start_datetime', $year))->where('status', 'confirmed')->sum('total_tickets'),
            'total_revenue' => EventBooking::whereHas('event', fn($q) => $q->whereYear('start_datetime', $year))->where('status', 'confirmed')->sum('total_amount'),
            'total_cancelled' => EventBooking::whereHas('event', fn($q) => $q->whereYear('start_datetime', $year))->where('status', 'cancelled')->count(),
            'total_tickets' => Ticket::whereHas('event', fn($q) => $q->whereYear('start_datetime', $year))->where('status', 'valid')->count(),
        ];

        // Ticket type breakdown
        $ticketBreakdown = Ticket::whereHas('event', fn($q) => $q->whereYear('start_datetime', $year))
            ->where('status', 'valid')
            ->selectRaw('type, COUNT(*) as count, SUM(price) as revenue')
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        return view('admin.event-breakdown.index', compact(
            'events', 'monthlyCounts', 'stats', 'ticketBreakdown',
            'year', 'statusFilter', 'search', 'monthFilter'
        ));
    }

    /**
     * Show detailed breakdown for a single event
     */
    public function show(Event $event)
    {
        $event->load(['location', 'confirmedBookings.user', 'confirmedBookings.tickets', 'bookings']);

        $stats = [
            'total_bookings' => $event->bookings()->where('status', 'confirmed')->count(),
            'total_attendees' => $event->bookings()->where('status', 'confirmed')->sum('total_tickets'),
            'total_revenue' => $event->bookings()->where('status', 'confirmed')->sum('total_amount'),
            'cancelled_bookings' => $event->bookings()->where('status', 'cancelled')->count(),
            'capacity' => $event->capacity,
            'remaining' => $event->remaining_spaces,
            'occupancy_pct' => $event->capacity > 0 ? round(($event->booked_tickets / $event->capacity) * 100) : 0,
        ];

        // Ticket breakdown for this event
        $ticketBreakdown = Ticket::where('event_id', $event->id)
            ->where('status', 'valid')
            ->selectRaw('type, COUNT(*) as count, SUM(price) as revenue')
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        $bookings = $event->bookings()
            ->with(['user', 'tickets'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.event-breakdown.show', compact('event', 'stats', 'ticketBreakdown', 'bookings'));
    }
}

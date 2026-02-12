<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventBooking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function customer()
    {
        $user = auth()->user();
        
        // Get user's booked events
        $bookedEvents = EventBooking::where('user_id', $user->id)
            ->with(['event.location'])
            ->whereHas('event', function($query) {
                $query->where('start_datetime', '>=', now())->where('status', 'active');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Get recommended events based on booked events or most recent
        $recommendedEvents = $this->getRecommendedEvents($user, $bookedEvents);

        return view('dashboards.customer', compact('bookedEvents', 'recommendedEvents'));
    }

    /**
     * Calculate recommended events based on user's booking tags or most recent events
     */
    private function getRecommendedEvents($user, $bookedEvents)
    {
        $bookedEventIds = $bookedEvents->pluck('event_id')->toArray();

        if ($bookedEvents->isNotEmpty()) {
            // Get all tags from booked events
            $bookedEventsTags = Event::whereIn('id', $bookedEventIds)
                ->with('tags')
                ->get()
                ->pluck('tags')
                ->flatten()
                ->pluck('id')
                ->unique();

            if ($bookedEventsTags->isNotEmpty()) {
                // Find events with matching tags (excluding already booked)
                $recommendedEvents = Event::where('status', 'active')
                    ->where('start_datetime', '>=', now())
                    ->whereNotIn('id', $bookedEventIds)
                    ->with(['tags', 'location'])
                    ->get()
                    ->map(function($event) use ($bookedEventsTags) {
                        $eventTags = $event->tags->pluck('id');
                        $matchingTags = $eventTags->intersect($bookedEventsTags);
                        $event->match_percentage = $bookedEventsTags->count() > 0 
                            ? round(($matchingTags->count() / $bookedEventsTags->count()) * 100)
                            : 0;
                        $event->matching_tags_count = $matchingTags->count();
                        return $event;
                    })
                    ->filter(function($event) {
                        return $event->matching_tags_count > 0;
                    })
                    ->sortByDesc('match_percentage')
                    ->take(6)
                    ->values();

                if ($recommendedEvents->isNotEmpty()) {
                    return $recommendedEvents;
                }
            }
        }

        // Fallback: Get most recent upcoming events
        return Event::where('status', 'active')
            ->where('start_datetime', '>=', now())
            ->whereNotIn('id', $bookedEventIds)
            ->with(['tags', 'location'])
            ->orderBy('start_datetime', 'asc')
            ->take(6)
            ->get();
    }

    public function staff()
    {
        $role = auth()->user()->role;
        return view('dashboards.staff', compact('role'));
    }
}

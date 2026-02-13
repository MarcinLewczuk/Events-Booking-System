<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\Tag;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        // Get user's interested tags if logged in (for recommendations)
        $userTags = auth()->check() ? auth()->user()->interestedTags->pluck('id') : collect();

        // Get upcoming events for carousel (top 5, no filters applied)
        $upcomingEvents = Event::with(['location', 'category', 'tags'])
            ->where('status', 'active')
            ->where('start_datetime', '>', Carbon::now())
            ->orderBy('start_datetime', 'asc')
            ->take(5)
            ->get();

        // Get filtered events for the grid
        $query = Event::with(['location', 'category', 'tags'])
            ->where('status', 'active')
            ->orderBy('start_datetime', 'asc');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by tags
        $selectedTags = $request->input('tags', []);
        if (!empty($selectedTags)) {
            $query->whereHas('tags', function ($q) use ($selectedTags) {
                $q->whereIn('tags.id', $selectedTags);
            });
        }

        // Filter by date range
        if ($request->filled('date_filter')) {
            $dateFilter = $request->date_filter;
            
            switch ($dateFilter) {
                case 'this_week':
                    $query->whereBetween('start_datetime', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                    
                case 'this_month':
                    $query->whereBetween('start_datetime', [
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth()
                    ]);
                    break;
                    
                case 'next_month':
                    $query->whereBetween('start_datetime', [
                        Carbon::now()->addMonth()->startOfMonth(),
                        Carbon::now()->addMonth()->endOfMonth()
                    ]);
                    break;
                    
                case 'upcoming':
                    $query->where('start_datetime', '>', Carbon::now());
                    break;
            }
        }

        // Custom date range filter (native date input uses YYYY-MM-DD)
        if ($request->filled('start_date')) {
            $query->where('start_datetime', '>=', Carbon::parse($request->start_date)->startOfDay());
        }

        if ($request->filled('end_date')) {
            $query->where('start_datetime', '<=', Carbon::parse($request->end_date)->endOfDay());
        }

        $events = $query->paginate(12);

        // Calculate recommendation scores for each event
        if ($userTags->isNotEmpty()) {
            foreach ($events as $event) {
                $event->recommendation_score = $this->calculateEventRecommendationScore($event, $userTags);
            }
        }

        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        // Get recommended events (separate query, top matches for logged-in user)
        $recommendedEvents = collect();
        if ($userTags->isNotEmpty()) {
            $recommendedEvents = Event::with(['location', 'category', 'tags'])
                ->where('status', 'active')
                ->where('start_datetime', '>', Carbon::now())
                ->whereHas('tags', function ($q) use ($userTags) {
                    $q->whereIn('tags.id', $userTags);
                })
                ->orderBy('start_datetime', 'asc')
                ->take(6)
                ->get();

            foreach ($recommendedEvents as $event) {
                $event->recommendation_score = $this->calculateEventRecommendationScore($event, $userTags);
            }

            // Sort by recommendation score descending
            $recommendedEvents = $recommendedEvents->sortByDesc('recommendation_score')->values();
        }

        return view('corporate.events.index', compact('events', 'categories', 'tags', 'upcomingEvents', 'recommendedEvents', 'userTags', 'selectedTags'));
    }

    public function show($id)
    {
        $event = Event::with(['location', 'category', 'tags'])->findOrFail($id);
        return view('corporate.events.show', compact('event'));
    }

    /**
     * Calculate recommendation score for an event based on user's interested tags.
     * Returns a percentage (0-100) indicating how well the event matches the user's preferences.
     */
    private function calculateEventRecommendationScore($event, $userTags)
    {
        if ($userTags->isEmpty()) {
            return 0;
        }

        $eventTags = $event->tags->pluck('id');

        if ($eventTags->isEmpty()) {
            return 0;
        }

        $matchingTags = $eventTags->intersect($userTags);

        return round(($matchingTags->count() / $userTags->count()) * 100);
    }
}


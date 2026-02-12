<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        // Get upcoming events for carousel (top 5, no filters applied)
        $upcomingEvents = Event::with(['location', 'category'])
            ->where('status', 'active')
            ->where('start_datetime', '>', Carbon::now())
            ->orderBy('start_datetime', 'asc')
            ->take(5)
            ->get();

        // Get filtered events for the grid
        $query = Event::with(['location', 'category'])
            ->where('status', 'active')
            ->orderBy('start_datetime', 'asc');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
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
        $categories = Category::orderBy('name')->get();

        return view('corporate.events.index', compact('events', 'categories', 'upcomingEvents'));
    }

    public function show($id)
    {
        $event = Event::with(['location', 'category'])->findOrFail($id);
        return view('corporate.events.show', compact('event'));
    }
}


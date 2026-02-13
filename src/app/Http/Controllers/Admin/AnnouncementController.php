<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']); // Admin only
    }

    /**
     * Display a listing of announcements
     */
    public function index(Request $request)
    {
        $query = Announcement::with(['event', 'creator']);

        // Filter by topic
        if ($request->filled('topic')) {
            if ($request->topic === 'general') {
                $query->general();
            } else {
                $query->topic($request->topic);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $announcements = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement
     */
    public function create()
    {
        $events = Event::where('status', 'active')
            ->where('start_datetime', '>=', now())
            ->with(['location', 'tags'])
            ->orderBy('start_datetime', 'asc')
            ->get();

        return view('admin.announcements.create', compact('events'));
    }

    /**
     * Generate auto message for auction or event
     */
    public function generateMessage(Request $request)
    {
        $request->validate([
            'type' => 'required|in:event',
            'id' => 'required|integer',
        ]);

        try {
            $message = Announcement::generateEventMessage($request->id);

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Could not generate message: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Store a newly created announcement
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'topic' => 'required|in:event,general',
            'event_id' => 'required_if:topic,event|nullable|exists:events,id',
        ]);

        // Prepare data
        $data = [
            'title' => $request->title,
            'message' => $request->message,
            'topic' => $request->topic,
            'created_by' => auth()->id(),
            'auction_id' => null,
            'catalogue_id' => null,
            'event_id' => null,
        ];

        // Set event ID if event-specific
        if ($request->topic === 'event' && $request->event_id) {
            $data['event_id'] = $request->event_id;
        }

        Announcement::create($data);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified announcement
     */
    public function show(Announcement $announcement)
    {
        $announcement->load(['event', 'creator']);
        
        return view('admin.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement
     */
    public function edit(Announcement $announcement)
    {
        $events = Event::where('status', 'active')
            ->where('start_datetime', '>=', now())
            ->with(['location', 'tags'])
            ->orderBy('start_datetime', 'asc')
            ->get();

        // Include the currently linked event even if it's past
        if ($announcement->event_id && !$events->contains('id', $announcement->event_id)) {
            $currentEvent = Event::with(['location', 'tags'])->find($announcement->event_id);
            if ($currentEvent) {
                $events->prepend($currentEvent);
            }
        }

        return view('admin.announcements.edit', compact('announcement', 'events'));
    }

    /**
     * Update the specified announcement
     */
    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'topic' => 'required|in:event,general',
            'event_id' => 'required_if:topic,event|nullable|exists:events,id',
        ]);

        // Prepare data
        $data = [
            'title' => $request->title,
            'message' => $request->message,
            'topic' => $request->topic,
            'auction_id' => null,
            'catalogue_id' => null,
            'event_id' => null,
        ];

        // Set event ID if event-specific
        if ($request->topic === 'event' && $request->event_id) {
            $data['event_id'] = $request->event_id;
        }

        $announcement->update($data);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified announcement
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}
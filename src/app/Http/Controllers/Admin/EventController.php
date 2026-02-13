<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use App\Models\Location;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * By policy, only admin/staff with event management role can access
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff')->only(['index', 'show']);
        $this->middleware('role:admin')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of events
     * 
     * Features:
     * - Search by title, description, location
     * - Filter by status (draft, upcoming, past, cancelled)
     * - Sort by date, title, status
     * - Pagination
     */
    public function index(Request $request)
    {
        $query = Event::with(['location', 'category']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('location', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('start_datetime', '>=', Carbon::parse($request->date_from)->startOfDay());
        }
        if ($request->filled('date_to')) {
            $query->where('start_datetime', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        // Sort options
        $sortBy = $request->get('sort', 'upcoming');
        switch ($sortBy) {
            case 'upcoming':
                $query->orderBy('start_datetime', 'asc');
                break;
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->orderBy('start_datetime', 'asc');
        }

        $events = $query->paginate(20)->withQueryString();

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show a single event
     */
    public function show(Event $event)
    {
        $event->load(['location', 'category', 'bookings', 'tags']);
        
        $stats = [
            'total_bookings' => $event->bookings()->where('status', 'confirmed')->count(),
            'total_tickets' => $event->confirmedBookings()->sum('total_tickets'),
            'remaining_capacity' => $event->remaining_spaces,
            'booking_value' => $event->confirmedBookings()->sum('total_amount'),
        ];

        return view('admin.events.show', compact('event', 'stats'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $location = Location::first();
        
        return view('admin.events.create', compact('categories', 'tags', 'location'));
    }

    /**
     * Store a newly created event
     * 
     * Validation rules:
     * - Title: required, unique, max 255 characters
     * - Description: required, min 50 characters
     * - Dates: start must be before end, both required
     * - Capacity: required, positive integer
     * - Pricing: optional but if provided must be positive
     * - Location & Category: required
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:events',
            'description' => 'required|string|min:50|max:2000',
            'itinerary' => 'nullable|string|max:5000',
            'start_datetime' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'end_datetime' => 'required|date_format:Y-m-d\TH:i|after:start_datetime',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'capacity' => 'required|integer|min:1|max:100000',
            'is_paid' => 'boolean',
            'adult_price' => 'nullable|numeric|min:0.01|max:9999.99',
            'child_price' => 'nullable|numeric|min:0.01|max:9999.99',
            'concession_price' => 'nullable|numeric|min:0.01|max:9999.99',
            'primary_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'in:draft,active,cancelled',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Convert datetime strings to Carbon instances
        $validated['start_datetime'] = Carbon::createFromFormat('Y-m-d\TH:i', $validated['start_datetime']);
        $validated['end_datetime'] = Carbon::createFromFormat('Y-m-d\TH:i', $validated['end_datetime']);

        // If not paid event, clear prices
        if (!$validated['is_paid'] ?? false) {
            $validated['adult_price'] = null;
            $validated['child_price'] = null;
            $validated['concession_price'] = null;
        }

        // Handle primary image upload
        if ($request->hasFile('primary_image')) {
            $image = $request->file('primary_image');
            $imageName = 'event_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('events', $imageName, 'public');
            $validated['primary_image'] = $imagePath;
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $index => $image) {
                $imageName = 'event_gallery_' . time() . '_' . $index . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('events', $imageName, 'public');
                $galleryPaths[] = $imagePath;
            }
            $validated['gallery_images'] = $galleryPaths;
        }

        // Calculate duration
        $validated['duration_minutes'] = $validated['start_datetime']->diffInMinutes($validated['end_datetime']);

        $tagIds = $validated['tags'] ?? [];
        unset($validated['tags']);

        $event = Event::create($validated);
        $event->tags()->sync($tagIds);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Show the form for editing an event
     */
    public function edit(Event $event)
    {
        $event->load('tags');
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $location = Location::first();
        
        return view('admin.events.edit', compact('event', 'categories', 'tags', 'location'));
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:events,title,' . $event->id,
            'description' => 'required|string|min:50|max:2000',
            'itinerary' => 'nullable|string|max:5000',
            'start_datetime' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'end_datetime' => 'required|date_format:Y-m-d\TH:i|after:start_datetime',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'capacity' => 'required|integer|min:1|max:100000',
            'is_paid' => 'boolean',
            'adult_price' => 'nullable|numeric|min:0.01|max:9999.99',
            'child_price' => 'nullable|numeric|min:0.01|max:9999.99',
            'concession_price' => 'nullable|numeric|min:0.01|max:9999.99',
            'primary_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'in:draft,active,cancelled',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Convert datetime strings to Carbon instances
        $validated['start_datetime'] = Carbon::createFromFormat('Y-m-d\TH:i', $validated['start_datetime']);
        $validated['end_datetime'] = Carbon::createFromFormat('Y-m-d\TH:i', $validated['end_datetime']);

        // If not paid event, clear prices
        if (!$validated['is_paid'] ?? false) {
            $validated['adult_price'] = null;
            $validated['child_price'] = null;
            $validated['concession_price'] = null;
        }

        // Handle primary image upload
        if ($request->hasFile('primary_image')) {
            // Delete old image if it exists
            if ($event->primary_image && Storage::disk('public')->exists($event->primary_image)) {
                Storage::disk('public')->delete($event->primary_image);
            }
            
            $image = $request->file('primary_image');
            $imageName = 'event_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('events', $imageName, 'public');
            $validated['primary_image'] = $imagePath;
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            // Delete old gallery images if they exist
            if ($event->gallery_images && is_array($event->gallery_images)) {
                foreach ($event->gallery_images as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $index => $image) {
                $imageName = 'event_gallery_' . time() . '_' . $index . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('events', $imageName, 'public');
                $galleryPaths[] = $imagePath;
            }
            $validated['gallery_images'] = $galleryPaths;
        }

        // Calculate duration
        $validated['duration_minutes'] = $validated['start_datetime']->diffInMinutes($validated['end_datetime']);

        $tagIds = $validated['tags'] ?? [];
        unset($validated['tags']);

        $event->update($validated);
        $event->tags()->sync($tagIds);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Delete an event with confirmation
     * 
     * Rules:
     * - Can only delete draft events
     * - Cannot delete events with confirmed bookings
     */
    public function destroy(Event $event)
    {
        // Prevent deletion of events with bookings
        if ($event->confirmedBookings()->exists()) {
            return back()->with('error', 'Cannot delete event with confirmed bookings. Cancel the event instead.');
        }

        // Check if draft
        if ($event->status !== 'draft') {
            return back()->with('error', 'Can only delete draft events. Set status to cancelled instead.');
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }
}

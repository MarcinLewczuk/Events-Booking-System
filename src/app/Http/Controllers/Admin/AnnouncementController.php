<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Auction;
use App\Models\Catalogue;
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
        $query = Announcement::with(['auction', 'catalogue', 'creator']);

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
        $auctions = Auction::where('approval_status', 'approved')
            ->with(['catalogue.items.tags', 'catalogue.items.category', 'location'])
            ->orderBy('auction_date', 'desc')
            ->get();
        
        $catalogues = Catalogue::where('status', 'published')
            ->orderBy('name')
            ->get();

        return view('admin.announcements.create', compact('auctions', 'catalogues'));
    }

    /**
     * Generate auto message for auction
     */
    public function generateMessage(Request $request)
    {
        $request->validate([
            'auction_id' => 'required|exists:auctions,id',
        ]);

        $message = Announcement::generateAuctionMessage($request->auction_id);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Store a newly created announcement
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'topic' => 'required|in:auction,catalogue,general',
            'auction_id' => 'required_if:topic,auction|nullable|exists:auctions,id',
            'catalogue_id' => 'required_if:topic,catalogue|nullable|exists:catalogues,id',
        ]);

        // Prepare data
        $data = [
            'title' => $request->title,
            'message' => $request->message,
            'topic' => $request->topic,
            'created_by' => auth()->id(),
            'auction_id' => null,
            'catalogue_id' => null,
        ];

        // Set IDs based on topic
        if ($request->topic === 'auction' && $request->auction_id) {
            $data['auction_id'] = $request->auction_id;
        } elseif ($request->topic === 'catalogue' && $request->catalogue_id) {
            $data['catalogue_id'] = $request->catalogue_id;
        }
        // If topic is 'general', both IDs remain null

        Announcement::create($data);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified announcement
     */
    public function show(Announcement $announcement)
    {
        $announcement->load(['auction', 'catalogue', 'creator']);
        
        return view('admin.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement
     */
    public function edit(Announcement $announcement)
    {
        $auctions = Auction::where('approval_status', 'approved')
            ->with(['catalogue.items.tags', 'catalogue.items.category', 'location'])
            ->orderBy('auction_date', 'desc')
            ->get();
        
        $catalogues = Catalogue::where('status', 'published')
            ->orderBy('name')
            ->get();

        return view('admin.announcements.edit', compact('announcement', 'auctions', 'catalogues'));
    }

    /**
     * Update the specified announcement
     */
    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'topic' => 'required|in:auction,catalogue,general',
            'auction_id' => 'required_if:topic,auction|nullable|exists:auctions,id',
            'catalogue_id' => 'required_if:topic,catalogue|nullable|exists:catalogues,id',
        ]);

        // Prepare data
        $data = [
            'title' => $request->title,
            'message' => $request->message,
            'topic' => $request->topic,
            'auction_id' => null,
            'catalogue_id' => null,
        ];

        // Set IDs based on topic
        if ($request->topic === 'auction' && $request->auction_id) {
            $data['auction_id'] = $request->auction_id;
        } elseif ($request->topic === 'catalogue' && $request->catalogue_id) {
            $data['catalogue_id'] = $request->catalogue_id;
        }
        // If topic is 'general', both IDs remain null

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
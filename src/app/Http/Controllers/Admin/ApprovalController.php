<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Auction;
use App\Models\Catalogue;
use App\Models\Category;
use App\Models\Band;
use App\Models\Tag;
use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'approver']);
    }

    /**
     * Show items pending approval
     */
    public function items(Request $request)
    {
        $query = Item::where('status', 'awaiting_approval')
            ->with(['category', 'primaryImage', 'creator', 'customer']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('detailed_description', 'like', "%{$search}%")
                  ->orWhere('creator', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by location
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // Filter by creator
        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('estimated_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('estimated_price', '<=', $request->max_price);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'current_stage_entered_at');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $items = $query->paginate(20)->withQueryString();

        // Get filter options
        $categories = Category::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $creators = User::whereIn('id', function($q) {
            $q->select('created_by')
              ->from('items')
              ->where('status', 'awaiting_approval')
              ->whereNotNull('created_by');
        })->orderBy('first_name')->get();

        return view('admin.approvals.items', compact('items', 'categories', 'locations', 'creators'));
    }

    /**
     * Show single item for approval with edit capability
     */
    public function showItem(Item $item)
    {
        // Check if item is awaiting approval
        if ($item->status !== 'awaiting_approval') {
            return redirect()->route('admin.approvals.items')
                ->withErrors(['error' => 'This item is not awaiting approval.']);
        }

        $item->load(['category', 'band', 'tags', 'images', 'primaryImage', 'creator', 'customer', 'approver']);
        
        $categories = Category::orderBy('name')->get();
        $bands = Band::orderBy('min_price')->get();
        $tags = Tag::orderBy('name')->get();
        $customers = User::where('role', 'customer')->orderBy('first_name')->get();
        $locations = Location::orderBy('name')->get();

        return view('admin.approvals.items-detail', compact('item', 'categories', 'bands', 'tags', 'customers', 'locations'));
    }

    /**
     * Show auctions pending approval
     */
    public function auctions(Request $request)
    {
        $query = Auction::where('approval_status', 'awaiting_approval')
            ->with(['catalogue', 'location', 'creator']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by location
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // Filter by creator
        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('auction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('auction_date', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'approval_status_changed_at');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $auctions = $query->paginate(20)->withQueryString();

        // Get filter options
        $locations = Location::orderBy('name')->get();
        $creators = User::whereIn('id', function($q) {
            $q->select('created_by')
              ->from('auctions')
              ->where('approval_status', 'awaiting_approval')
              ->whereNotNull('created_by');
        })->orderBy('first_name')->get();

        return view('admin.approvals.auctions', compact('auctions', 'locations', 'creators'));
    }

    /**
     * Show single auction for approval with edit capability
     */
    public function showAuction(Auction $auction)
    {
        // Check if auction is awaiting approval
        if ($auction->approval_status !== 'awaiting_approval') {
            return redirect()->route('admin.approvals.auctions')
                ->withErrors(['error' => 'This auction is not awaiting approval.']);
        }

        $auction->load(['catalogue.items.primaryImage', 'location', 'creator', 'approver']);
        
        $catalogues = Catalogue::where('status', 'published')->orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('admin.approvals.auction-detail', compact('auction', 'catalogues', 'locations'));
    }

    /**
     * Show catalogues pending approval
     */
    public function catalogues(Request $request)
    {
        $query = Catalogue::where('status', 'awaiting_approval')
            ->with(['category', 'creator', 'items']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by creator
        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        // Filter by item count
        if ($request->filled('min_items')) {
            $query->has('items', '>=', $request->min_items);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'updated_at');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $catalogues = $query->paginate(20)->withQueryString();

        // Get filter options
        $categories = Category::orderBy('name')->get();
        $creators = User::whereIn('id', function($q) {
            $q->select('created_by')
              ->from('catalogues')
              ->where('status', 'awaiting_approval')
              ->whereNotNull('created_by');
        })->orderBy('first_name')->get();

        return view('admin.approvals.catalogues', compact('catalogues', 'categories', 'creators'));
    }

    /**
     * Show single catalogue for approval with edit capability
     */
    public function showCatalogue(Catalogue $catalogue)
    {
        // Check if catalogue is awaiting approval
        if ($catalogue->status !== 'awaiting_approval') {
            return redirect()->route('admin.approvals.catalogues')
                ->withErrors(['error' => 'This catalogue is not awaiting approval.']);
        }

        $catalogue->load(['category', 'items.primaryImage', 'items.category', 'creator']);
        
        $categories = Category::orderBy('name')->get();

        return view('admin.approvals.catalogue-detail', compact('catalogue', 'categories'));
    }
}
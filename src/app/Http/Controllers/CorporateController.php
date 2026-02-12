<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Location;
use App\Models\Band;
use App\Models\Auction;
use App\Models\Event;
use Illuminate\Http\Request;

class CorporateController extends Controller
{

    // Home page
    public function index()
    {
        // Get upcoming auctions for carousel (approved, future dates, with catalogues)
        $upcomingAuctions = Auction::with(['catalogue.items' => function($query) {
                $query->where('status', 'published')
                      ->with('primaryImage')
                      ->orderBy('estimated_price', 'desc')
                      ->take(4);
            }, 'location'])
            ->where('approval_status', 'approved')
            ->where('auction_date', '>=', now())
            ->orderBy('auction_date', 'asc')
            ->take(5)
            ->get()
            ->map(function($auction) {
                // Get top 4 most valuable items for carousel display
                if ($auction->catalogue) {
                    $auction->topItems = $auction->catalogue->items()
                        ->where('status', 'published')
                        ->with('primaryImage')
                        ->orderBy('estimated_price', 'desc')
                        ->take(4)
                        ->get();
                } else {
                    $auction->topItems = collect();
                }
                return $auction;
            });

        // Get most valuable items from catalogues of upcoming auctions
        $featuredItems = collect();
        foreach ($upcomingAuctions->take(3) as $auction) {
            if ($auction->catalogue) {
                $items = $auction->catalogue->items()
                    ->where('status', 'published')
                    ->orderBy('estimated_price', 'desc')
                    ->take(4)
                    ->get();
                
                foreach ($items as $item) {
                    $item->featured_auction = $auction;
                    $featuredItems->push($item);
                }
            }
        }
        $featuredItems = $featuredItems->take(8);

        // Get highlighted items (most valuable items with auctions)
        $highlightedItems = Item::with(['primaryImage', 'category', 'catalogues.auctions' => function($q) {
                $q->where('approval_status', 'approved')
                ->where('auction_date', '>=', now())
                ->orderBy('auction_date', 'asc');
            }])
            ->where('status', 'published')
            ->whereHas('catalogues.auctions', function($q) {
                $q->where('approval_status', 'approved')
                ->where('auction_date', '>=', now());
            })
            ->orderBy('estimated_price', 'desc')
            ->take(6)
            ->get()
            ->map(function($item) {
                // Get the next auction for this item
                $item->next_auction = $item->catalogues->flatMap->auctions->first();
                return $item;
            });

        // Get all locations with images
        $locations = Location::whereNotNull('image_path')
            ->orderBy('name')
            ->take(6)
            ->get();

        // Get upcoming events for carousel
        $upcomingEvents = Event::where('status', 'active')
            ->where('start_datetime', '>', now())
            ->orderBy('start_datetime', 'asc')
            ->take(5)
            ->get();

        return view('corporate.home', compact(
            'upcomingAuctions',
            'featuredItems',
            'highlightedItems',
            'locations',
            'upcomingEvents'
        ));
    }

    // About page
    public function about()
    {
        return view('corporate.about');
    }

    // Services page
    public function services()
    {
        return view('corporate.services');
    }

    // Contact page
    public function contact()
    {
        return view('corporate.contact');
    }
    // How to Bid page
    public function howToBid()
    {
        return view('corporate.howtobid');
    }

    // Sell With Us page
    public function sellWithUs()
    {
        return view('corporate.sellwithus');
    }

    // Valuation page
    public function valuation()
    {
        return view('corporate.valuation');
    }

    /**
     * Browse items - Public page with filtering
     */
    public function browseItems(Request $request)
    {
        $view = $request->input('view', 'grid'); // 'grid' or 'list'
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $locationId = $request->input('location_id');
        $bandId = $request->input('band_id');
        $tagIds = $request->input('tags', []);
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sortBy = $request->input('sort_by', 'newest'); // newest, oldest, price_asc, price_desc, title

        // Get user's interested tags if logged in
        $userTags = auth()->check() ? auth()->user()->interestedTags->pluck('id') : collect();

        // Base query - only published items
        $query = Item::with(['category', 'tags', 'primaryImage', 'location', 'band'])
            ->where('status', 'published');

        // Apply filters
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('detailed_description', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($locationId) {
            $query->where('location_id', $locationId);
        }

        if ($bandId) {
            $query->where('band_id', $bandId);
        }

        if (!empty($tagIds)) {
            $query->whereHas('tags', function($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            });
        }

        if ($minPrice) {
            $query->where('estimated_price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $query->where('estimated_price', '<=', $maxPrice);
        }

        if ($request->filled('creator')) {
            $query->where('creator', 'like', '%' . $request->creator . '%');
        }
        
        if ($request->filled('year_from')) {
            $query->where('year_of_creation', '>=', $request->year_from);
        }
        if ($request->filled('year_to')) {
            $query->where('year_of_creation', '<=', $request->year_to);
        }

        if ($request->filled('weight_from')) {
            $query->where('weight', '>=', $request->weight_from);
        }
        if ($request->filled('weight_to')) {
            $query->where('weight', '<=', $request->weight_to);
        }
        // Apply sorting
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_asc':
                $query->orderBy('estimated_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('estimated_price', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        // Get items
        $items = $query->paginate(12)->withQueryString();

        // Calculate recommendation scores for logged-in users
        if (auth()->check() && $userTags->isNotEmpty()) {
            $items->getCollection()->transform(function($item) use ($userTags) {
                $item->recommendation_score = $this->calculateItemRecommendationScore($item, $userTags);
                return $item;
            });

            // Get recommended items (top matches by percentage)
            $recommendedItems = Item::with(['tags', 'primaryImage', 'category'])
                ->where('status', 'published')
                ->whereHas('tags', function($q) use ($userTags) {
                    $q->whereIn('tags.id', $userTags);
                })
                ->get()
                ->map(function($item) use ($userTags) {
                    $item->recommendation_score = $this->calculateItemRecommendationScore($item, $userTags);
                    return $item;
                })
                ->sortByDesc('recommendation_score')
                ->take(4);
        } else {
            $recommendedItems = collect();
        }

        // Get filter options
        $categories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $bands = Band::orderBy('min_price')->get();
        $tags = Tag::orderBy('name')->get();

        return view('corporate.items.index', compact(
            'items',
            'categories',
            'locations',
            'bands',
            'tags',
            'recommendedItems',
            'view',
            'search',
            'categoryId',
            'locationId',
            'bandId',
            'tagIds',
            'minPrice',
            'maxPrice',
            'sortBy',
            'userTags'
        ));
    }

    /**
     * Show single item - Public page
     */
    public function showItem(Item $item)
    {
        // Ensure item is published
        if ($item->status !== 'published') {
            abort(404);
        }


        $item->load(['catalogues.auctions', 'category', 'tags', 'primaryImage', 'images', 'location', 'band', 'creator']);

        // Get similar items based on category and tags
        $itemTags = $item->tags->pluck('id');
        
        $similarItems = Item::where('status', 'published')
            ->where('id', '!=', $item->id)
            ->where(function($q) use ($item, $itemTags) {
                $q->where('category_id', $item->category_id)
                  ->orWhereHas('tags', function($q) use ($itemTags) {
                      $q->whereIn('tags.id', $itemTags);
                  });
            })
            ->with(['tags', 'primaryImage', 'category'])
            ->take(4)
            ->get();

        return view('corporate.items.show', compact('item', 'similarItems'));
    }

    /**
     * Calculate recommendation score for an item
     */
    private function calculateItemRecommendationScore($item, $userTags)
    {
        if ($userTags->isEmpty()) {
            return 0;
        }
        
        $itemTags = $item->tags->pluck('id');
        $matchingTags = $itemTags->intersect($userTags);
        
        return ($matchingTags->count() / $userTags->count()) * 100;
    }

    /**
     * Browse auctions - Public page with filtering (combines auction and catalogue info)
     */
    public function browseAuctions(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $locationId = $request->input('location_id');
        $tagIds = $request->input('tags', []);
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $status = $request->input('status', 'upcoming'); // upcoming, scheduled, all
        $sortBy = $request->input('sort_by', 'date_asc'); // date_asc, date_desc, title

        // Get user's interested tags if logged in
        $userTags = auth()->check() ? auth()->user()->interestedTags->pluck('id') : collect();

        // Base query - only approved auctions
        $query = Auction::with(['catalogue.category', 'catalogue.items.tags', 'location'])
            ->where('approval_status', 'approved');

        // Filter by status
        if ($status === 'upcoming') {
            $query->where('auction_date', '>=', now())
                ->where('status', '!=', 'closed');
        } elseif ($status === 'scheduled') {
            $query->whereIn('status', ['scheduled', 'open']);
        }

        // Apply filters
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhereHas('catalogue', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($categoryId) {
            $query->whereHas('catalogue', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        if ($locationId) {
            $query->where('location_id', $locationId);
        }

        if (!empty($tagIds)) {
            $query->whereHas('catalogue.items.tags', function($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            });
        }

        if ($dateFrom) {
            $query->where('auction_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('auction_date', '<=', $dateTo);
        }

        // Apply sorting
        switch ($sortBy) {
            case 'date_desc':
                $query->orderBy('auction_date', 'desc')->orderBy('start_time', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'date_asc':
            default:
                $query->orderBy('auction_date', 'asc')->orderBy('start_time', 'asc');
                break;
        }

        // Get auctions
        $auctions = $query->paginate(9)->withQueryString();

        // Calculate recommendation scores for logged-in users
        if (auth()->check() && $userTags->isNotEmpty()) {
            $auctions->getCollection()->transform(function($auction) use ($userTags) {
                $auction->recommendation_score = $this->calculateAuctionRecommendationScore($auction, $userTags);
                $auction->matching_items_count = $this->getAuctionMatchingItemsCount($auction, $userTags);
                return $auction;
            });

            // Get recommended auctions
            $recommendedAuctions = Auction::with(['catalogue.items.tags', 'location'])
                ->where('approval_status', 'approved')
                ->where('auction_date', '>=', now())
                ->whereHas('catalogue.items.tags', function($q) use ($userTags) {
                    $q->whereIn('tags.id', $userTags);
                })
                ->inRandomOrder()
                ->take(3)
                ->get()
                ->map(function($auction) use ($userTags) {
                    $auction->recommendation_score = $this->calculateAuctionRecommendationScore($auction, $userTags);
                    $auction->matching_items_count = $this->getAuctionMatchingItemsCount($auction, $userTags);
                    return $auction;
                });
        } else {
            $recommendedAuctions = collect();
        }

        // Get filter options
        $categories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('corporate.auctions.index', compact(
            'auctions',
            'recommendedAuctions',
            'categories',
            'locations',
            'tags',
            'search',
            'categoryId',
            'locationId',
            'tagIds',
            'dateFrom',
            'dateTo',
            'status',
            'sortBy',
            'userTags'
        ));
    }

    /**
     * Show single auction - Public page with catalogue items displayed in special layout
     */

    public function showAuction(Request $request, Auction $auction)
    {
        // Ensure auction is approved
        if ($auction->approval_status !== 'approved') {
            abort(404);
        }

        // Get view mode: 'bands' (default) or 'browse'
        $viewMode = $request->input('view', 'bands');

        // Get search and filter parameters
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $locationId = $request->input('location_id');
        $bandId = $request->input('band_id');
        $tagIds = $request->input('tags', []);
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $creator = $request->input('creator');
        $yearFrom = $request->input('year_from');
        $yearTo = $request->input('year_to');
        $weightFrom = $request->input('weight_from');
        $weightTo = $request->input('weight_to');
        $sortBy = $request->input('sort_by', 'newest');

        // Load relationships including band
        $auction->load([
            'catalogue.category',
            'catalogue.items.tags',
            'catalogue.items.primaryImage',
            'catalogue.items.images',
            'catalogue.items.category',
            'catalogue.items.location',
            'catalogue.items.band',
            'location',
            'announcements'
        ]);

        // Get user's interested tags if logged in
        $userTags = auth()->check() ? auth()->user()->interestedTags->pluck('id') : collect();

        // Get all catalogue items and apply filters
        $allItems = $auction->catalogue 
            ? $auction->catalogue->items
            : collect();

        // Apply search filter
        if ($search) {
            $allItems = $allItems->filter(function($item) use ($search) {
                return stripos($item->title, $search) !== false ||
                    stripos($item->short_description, $search) !== false ||
                    stripos($item->detailed_description, $search) !== false ||
                    stripos($item->creator, $search) !== false;
            });
        }

        // Apply category filter
        if ($categoryId) {
            $allItems = $allItems->where('category_id', $categoryId);
        }

        // Apply location filter
        if ($locationId) {
            $allItems = $allItems->where('location_id', $locationId);
        }

        // Apply band filter
        if ($bandId) {
            $allItems = $allItems->where('band_id', $bandId);
        }

        // Apply creator filter
        if ($creator) {
            $allItems = $allItems->filter(function($item) use ($creator) {
                return stripos($item->creator, $creator) !== false;
            });
        }

        // Apply tag filter
        if (!empty($tagIds)) {
            $allItems = $allItems->filter(function($item) use ($tagIds) {
                return $item->tags->pluck('id')->intersect($tagIds)->count() > 0;
            });
        }

        // Apply price range filter
        if ($minPrice) {
            $allItems = $allItems->filter(function($item) use ($minPrice) {
                return $item->estimated_price >= $minPrice;
            });
        }
        if ($maxPrice) {
            $allItems = $allItems->filter(function($item) use ($maxPrice) {
                return $item->estimated_price <= $maxPrice;
            });
        }

        // Apply year filter
        if ($yearFrom) {
            $allItems = $allItems->filter(function($item) use ($yearFrom) {
                return $item->year_of_creation && $item->year_of_creation >= $yearFrom;
            });
        }
        if ($yearTo) {
            $allItems = $allItems->filter(function($item) use ($yearTo) {
                return $item->year_of_creation && $item->year_of_creation <= $yearTo;
            });
        }

        // Apply weight filter
        if ($weightFrom) {
            $allItems = $allItems->filter(function($item) use ($weightFrom) {
                return $item->weight && $item->weight >= $weightFrom;
            });
        }
        if ($weightTo) {
            $allItems = $allItems->filter(function($item) use ($weightTo) {
                return $item->weight && $item->weight <= $weightTo;
            });
        }

        // Apply sorting for browse mode
        if ($viewMode === 'browse') {
            switch ($sortBy) {
                case 'oldest':
                    $allItems = $allItems->sortBy('created_at')->values();
                    break;
                case 'price_asc':
                    $allItems = $allItems->sortBy('estimated_price')->values();
                    break;
                case 'price_desc':
                    $allItems = $allItems->sortByDesc('estimated_price')->values();
                    break;
                case 'title':
                    $allItems = $allItems->sortBy('title')->values();
                    break;
                case 'newest':
                default:
                    $allItems = $allItems->sortByDesc('created_at')->values();
                    break;
            }
        }

        // Get personalized recommendations (top 4 matches) for logged-in users
        $recommendedItems = collect();
        if ($userTags->isNotEmpty() && $allItems->isNotEmpty()) {
            $recommendedItems = $allItems->map(function($item) use ($userTags) {
                $itemTags = $item->tags->pluck('id');
                $matchingTags = $itemTags->intersect($userTags);
                $item->recommendation_score = $matchingTags->count();
                return $item;
            })
            ->filter(function($item) {
                return $item->recommendation_score > 0;
            })
            ->sortByDesc('recommendation_score')
            ->take(4)
            ->values();
        }

        // Organize items by band (for bands view)
        $bands = Band::orderByRaw('CASE WHEN max_price = 0 OR max_price IS NULL THEN 999999999 ELSE max_price END DESC')
            ->get();
        
        $itemsByBand = [];
        foreach ($bands as $band) {
            $bandItems = $allItems->where('band_id', $band->id)->sortByDesc('estimated_price')->values();
            if ($bandItems->isNotEmpty()) {
                $itemsByBand[] = [
                    'band' => $band,
                    'items' => $bandItems
                ];
            }
        }

        $unassignedItems = $allItems->whereNull('band_id')->sortByDesc('estimated_price')->values();
        if ($unassignedItems->isNotEmpty()) {
            $itemsByBand[] = [
                'band' => null,
                'items' => $unassignedItems
            ];
        }

        // Get other upcoming auctions
        $otherAuctions = Auction::with(['catalogue', 'location'])
            ->where('approval_status', 'approved')
            ->where('id', '!=', $auction->id)
            ->where('auction_date', '>=', now())
            ->orderBy('auction_date', 'asc')
            ->take(3)
            ->get();

        // Get filter options
        $categories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $allBands = Band::orderByRaw('CASE WHEN max_price = 0 OR max_price IS NULL THEN 999999999 ELSE max_price END DESC')->get();
        $tags = Tag::orderBy('name')->get();

        return view('corporate.auctions.show', compact(
            'auction',
            'itemsByBand',
            'allItems',
            'recommendedItems',
            'otherAuctions',
            'userTags',
            'categories',
            'locations',
            'allBands',
            'tags',
            'viewMode',
            'search',
            'categoryId',
            'locationId',
            'bandId',
            'tagIds',
            'minPrice',
            'maxPrice',
            'creator',
            'yearFrom',
            'yearTo',
            'weightFrom',
            'weightTo',
            'sortBy'
        ));
    }

    /**
     * Calculate recommendation score for an auction based on its catalogue's items' tags
     */
    private function calculateAuctionRecommendationScore($auction, $userTags)
    {
        if ($userTags->isEmpty() || !$auction->catalogue || $auction->catalogue->items->isEmpty()) {
            return 0;
        }
        
        // Get all unique tags from all items in the auction's catalogue
        $auctionTags = $auction->catalogue->items->pluck('tags')->flatten()->pluck('id')->unique();
        $matchingTags = $auctionTags->intersect($userTags);
        
        // Score: number of matching tags / total user interested tags * 100
        return ($matchingTags->count() / $userTags->count()) * 100;
    }

    /**
     * Count items in auction that match user's tags
     */
    private function getAuctionMatchingItemsCount($auction, $userTags)
    {
        if ($userTags->isEmpty() || !$auction->catalogue) {
            return 0;
        }
        
        return $auction->catalogue->items->filter(function($item) use ($userTags) {
            return $item->tags->pluck('id')->intersect($userTags)->count() > 0;
        })->count();
    }

    /**
     * Public locations page with tabs
     */
    public function locations(Request $request)
    {
        // Get all locations with their relationships
        $locations = Location::orderBy('name')->get();

        // Get the active location (from query param or first location)
        $activeLocationId = $request->input('location', $locations->first()?->id);
        $activeLocation = $locations->firstWhere('id', $activeLocationId);

        if ($activeLocation) {
            // Get upcoming auctions for this location
            $upcomingAuctions = $activeLocation->auctions()
                ->where('auction_date', '>=', now())
                ->where('approval_status', 'approved')
                ->with(['catalogue.items'])
                ->orderBy('auction_date')
                ->take(6)
                ->get();

            // Get top valuable items at this location (published items with images)
            $topItems = $activeLocation->items()
                ->where('status', 'published')
                ->with(['primaryImage', 'category'])
                ->orderBy('estimated_price', 'desc')
                ->take(6)
                ->get();
            
            // Count total items at location
            $totalItems = $activeLocation->items()
                ->where('status', 'published')
                ->count();
        } else {
            $upcomingAuctions = collect();
            $topItems = collect();
            $totalItems = 0;
        }

        return view('corporate.locations.index', compact(
            'locations',
            'activeLocation',
            'upcomingAuctions',
            'topItems',
            'totalItems'
        ));
    }
}
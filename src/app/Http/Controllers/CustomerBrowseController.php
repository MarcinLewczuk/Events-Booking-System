<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Catalogue;
use App\Models\Auction;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerBrowseController extends Controller
{
    /**
     * Display approved items with recommendations
     */
    public function items(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $tagId = $request->input('tag_id');
        
        // Get user's interested tags
        $userTags = auth()->user()->interestedTags->pluck('id');
        
        // Base query for approved items only
        $query = Item::with(['category', 'tags', 'primaryImage'])
            ->where('status', 'approved');
        
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
        
        if ($tagId) {
            $query->whereHas('tags', function($q) use ($tagId) {
                $q->where('tags.id', $tagId);
            });
        }
        
        // Get items and calculate recommendation scores
        $items = $query->get()->map(function($item) use ($userTags) {
            $item->recommendation_score = $this->calculateItemRecommendationScore($item, $userTags);
            return $item;
        })->sortByDesc('recommendation_score');
        
        // Get recommended items (top matches based on user tags)
        $recommendedItems = $items->where('recommendation_score', '>', 0)->take(6);
        
        // Paginate manually
        $perPage = 12;
        $page = $request->input('page', 1);
        $itemsCollection = $items->forPage($page, $perPage);
        
        return view('customer.items.index', [
            'items' => $itemsCollection,
            'recommendedItems' => $recommendedItems,
            'search' => $search,
            'categoryId' => $categoryId,
            'tagId' => $tagId,
            'total' => $items->count(),
            'perPage' => $perPage,
            'currentPage' => $page
        ]);
    }
    
    /**
     * Display approved catalogues with recommendations
     */
    public function catalogues(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        
        // Get user's interested tags
        $userTags = auth()->user()->interestedTags->pluck('id');
        
        // Get published catalogues with their items
        $query = Catalogue::with(['category', 'items.tags'])
            ->where('status', 'published');
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        // Get catalogues and calculate recommendation scores
        $catalogues = $query->get()->map(function($catalogue) use ($userTags) {
            $catalogue->recommendation_score = $this->calculateCatalogueRecommendationScore($catalogue, $userTags);
            $catalogue->matching_items_count = $this->getMatchingItemsCount($catalogue, $userTags);
            return $catalogue;
        })->sortByDesc('recommendation_score');
        
        // Get recommended catalogues
        $recommendedCatalogues = $catalogues->where('recommendation_score', '>', 0)->take(4);
        
        // Paginate manually
        $perPage = 9;
        $page = $request->input('page', 1);
        $cataloguesCollection = $catalogues->forPage($page, $perPage);
        
        return view('customer.catalogues.index', [
            'catalogues' => $cataloguesCollection,
            'recommendedCatalogues' => $recommendedCatalogues,
            'search' => $search,
            'categoryId' => $categoryId,
            'total' => $catalogues->count(),
            'perPage' => $perPage,
            'currentPage' => $page
        ]);
    }
    
    /**
     * Display approved auctions with recommendations
     */
    public function auctions(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        
        // Get user's interested tags
        $userTags = auth()->user()->interestedTags->pluck('id');
        
        // Get approved auctions with their catalogue and items
        $query = Auction::with(['catalogue.items.tags', 'location'])
            ->where('approval_status', 'approved');
        
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        // Get auctions and calculate recommendation scores
        $auctions = $query->get()->map(function($auction) use ($userTags) {
            $auction->recommendation_score = $this->calculateAuctionRecommendationScore($auction, $userTags);
            $auction->matching_items_count = $this->getAuctionMatchingItemsCount($auction, $userTags);
            return $auction;
        })->sortByDesc('recommendation_score');
        
        // Get recommended auctions
        $recommendedAuctions = $auctions->where('recommendation_score', '>', 0)->take(3);
        
        // Paginate manually
        $perPage = 6;
        $page = $request->input('page', 1);
        $auctionsCollection = $auctions->forPage($page, $perPage);
        
        return view('customer.auctions.index', [
            'auctions' => $auctionsCollection,
            'recommendedAuctions' => $recommendedAuctions,
            'search' => $search,
            'status' => $status,
            'total' => $auctions->count(),
            'perPage' => $perPage,
            'currentPage' => $page
        ]);
    }
    
    /**
     * Show single item detail
     */
    public function showItem(Item $item)
    {
        // Ensure item is approved
        if ($item->status !== 'approved') {
            abort(404);
        }
        
        $item->load(['category', 'tags', 'images', 'location', 'band']);
        
        // Get similar items based on tags
        $userTags = auth()->user()->interestedTags->pluck('id');
        $itemTags = $item->tags->pluck('id');
        
        $similarItems = Item::where('status', 'approved')
            ->where('id', '!=', $item->id)
            ->whereHas('tags', function($q) use ($itemTags) {
                $q->whereIn('tags.id', $itemTags);
            })
            ->with(['tags', 'primaryImage'])
            ->take(4)
            ->get();
        
        return view('customer.items.show', compact('item', 'similarItems'));
    }
    
    /**
     * Show single catalogue detail
     */
    public function showCatalogue(Catalogue $catalogue)
    {
        // Ensure catalogue is published
        if ($catalogue->status !== 'published') {
            abort(404);
        }
        
        $catalogue->load(['category', 'items.tags', 'items.primaryImage', 'items.category']);
        
        // Calculate recommendation for user
        $userTags = auth()->user()->interestedTags->pluck('id');
        $recommendedItems = $catalogue->items->filter(function($item) use ($userTags) {
            return $item->tags->pluck('id')->intersect($userTags)->count() > 0;
        });
        
        return view('customer.catalogues.show', compact('catalogue', 'recommendedItems'));
    }
    
    /**
     * Show single auction detail
     */
    public function showAuction(Auction $auction)
    {
        // Ensure auction is approved
        if ($auction->approval_status !== 'approved') {
            abort(404);
        }
        
        $auction->load(['catalogue.items.tags', 'catalogue.items.primaryImage', 'location']);
        
        // Get recommended items from this auction based on user tags
        $userTags = auth()->user()->interestedTags->pluck('id');
        $recommendedItems = collect();
        
        if ($auction->catalogue) {
            $recommendedItems = $auction->catalogue->items->filter(function($item) use ($userTags) {
                return $item->tags->pluck('id')->intersect($userTags)->count() > 0;
            })->take(6);
        }
        
        return view('customer.auctions.show', compact('auction', 'recommendedItems'));
    }
    
    /**
     * Calculate recommendation score for an item based on user's tag preferences
     */
    private function calculateItemRecommendationScore($item, $userTags)
    {
        if ($userTags->isEmpty()) {
            return 0;
        }
        
        $itemTags = $item->tags->pluck('id');
        $matchingTags = $itemTags->intersect($userTags);
        
        // Score: number of matching tags / total user interested tags * 100
        return ($matchingTags->count() / $userTags->count()) * 100;
    }
    
    /**
     * Calculate recommendation score for a catalogue based on its items' tags
     */
    private function calculateCatalogueRecommendationScore($catalogue, $userTags)
    {
        if ($userTags->isEmpty() || $catalogue->items->isEmpty()) {
            return 0;
        }
        
        // Get all unique tags from all items in the catalogue
        $catalogueTags = $catalogue->items->pluck('tags')->flatten()->pluck('id')->unique();
        $matchingTags = $catalogueTags->intersect($userTags);
        
        // Score: number of matching tags / total user interested tags * 100
        return ($matchingTags->count() / $userTags->count()) * 100;
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
     * Count items in catalogue that match user's tags
     */
    private function getMatchingItemsCount($catalogue, $userTags)
    {
        if ($userTags->isEmpty()) {
            return 0;
        }
        
        return $catalogue->items->filter(function($item) use ($userTags) {
            return $item->tags->pluck('id')->intersect($userTags)->count() > 0;
        })->count();
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
}
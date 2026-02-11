<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Item;
use App\Models\SeatBooking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function customer()
    {
        $user = auth()->user();
        $userTags = $user->interestedTags->pluck('id');
        
        // Get user's seat bookings
        $seatBookings = SeatBooking::where('user_id', $user->id)
            ->with(['auction.location'])
            ->whereHas('auction', function($query) {
                $query->where('auction_date', '>=', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get recommended auctions (max 3)
        $recommendedAuctions = collect();
        if ($userTags->isNotEmpty()) {
            $recommendedAuctions = Auction::where('approval_status', 'approved')
                ->where('auction_date', '>=', now())
                ->with(['catalogue.items.tags', 'location'])
                ->get()
                ->map(function($auction) use ($userTags) {
                    if ($auction->catalogue && $auction->catalogue->items->isNotEmpty()) {
                        // Get all tags from auction items
                        $auctionTags = $auction->catalogue->items->pluck('tags')->flatten()->pluck('id')->unique();
                        $matchingTags = $auctionTags->intersect($userTags);
                        $auction->match_percentage = $userTags->count() > 0 
                            ? round(($matchingTags->count() / $userTags->count()) * 100)
                            : 0;
                        $auction->matching_items_count = $auction->catalogue->items->filter(function($item) use ($userTags) {
                            return $item->tags->pluck('id')->intersect($userTags)->count() > 0;
                        })->count();
                        return $auction;
                    }
                    return null;
                })
                ->filter()
                ->sortByDesc('match_percentage')
                ->take(3)
                ->values();
        }
        
        // Get recommended items (top 6 by matching tag count)
        $recommendedItems = collect();
        if ($userTags->isNotEmpty()) {
            $recommendedItems = Item::where('status', 'published')
                ->with(['category', 'tags', 'primaryImage'])
                ->whereHas('tags', function($query) use ($userTags) {
                    $query->whereIn('tags.id', $userTags);
                })
                ->get()
                ->map(function($item) use ($userTags) {
                    $itemTags = $item->tags->pluck('id');
                    $matchingTags = $itemTags->intersect($userTags);
                    $item->matching_tags_count = $matchingTags->count();
                    $item->match_percentage = $userTags->count() > 0 
                        ? round(($matchingTags->count() / $userTags->count()) * 100)
                        : 0;
                    return $item;
                })
                ->sortByDesc('matching_tags_count')
                ->take(6)
                ->values();
        }
        
        return view('dashboards.customer', compact('recommendedAuctions', 'recommendedItems', 'userTags', 'seatBookings'));
    }

    public function staff()
    {
        $role = auth()->user()->role;
        return view('dashboards.staff', compact('role'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:customer']);
    }

    /**
     * Display customer's relevant announcements
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get user's interested tag IDs
        $userTagIds = $user->interestedTags->pluck('id')->toArray();
        
        if (empty($userTagIds)) {
            // If user has no interested tags, show empty inbox
            return view('customer.inbox.index', [
                'announcements' => collect(),
                'userHasNoTags' => true,
            ]);
        }

        // Get all announcements
        $allAnnouncements = Announcement::with([
            'auction.catalogue.items.tags',
            'catalogue.items.tags',
            'creator'
        ])->latest()->get();

        // Filter announcements based on matching tags
        $relevantAnnouncements = $allAnnouncements->filter(function ($announcement) use ($userTagIds) {
            return $this->announcementMatchesUserTags($announcement, $userTagIds);
        });

        return view('customer.inbox.index', [
            'announcements' => $relevantAnnouncements,
            'userHasNoTags' => false,
        ]);
    }

    /**
     * Show single announcement
     */
    public function show(Announcement $announcement)
    {
        $user = auth()->user();
        $userTagIds = $user->interestedTags->pluck('id')->toArray();

        // Check if user should have access to this announcement
        if (empty($userTagIds) || !$this->announcementMatchesUserTags($announcement, $userTagIds)) {
            abort(404, 'Announcement not found or not relevant to you.');
        }

        $announcement->load([
            'auction.catalogue.items.tags',
            'catalogue.items.tags',
            'creator'
        ]);

        // Get matching tags for display
        $matchingTags = $this->getMatchingTags($announcement, $userTagIds);

        return view('customer.inbox.show', compact('announcement', 'matchingTags'));
    }

    /**
     * Check if announcement matches user's tags
     */
    private function announcementMatchesUserTags(Announcement $announcement, array $userTagIds): bool
    {
        $items = collect();

        // Get items from auction or catalogue
        if ($announcement->auction_id && $announcement->auction) {
            if ($announcement->auction->catalogue) {
                $items = $announcement->auction->catalogue->items;
            }
        } elseif ($announcement->catalogue_id && $announcement->catalogue) {
            $items = $announcement->catalogue->items;
        }

        if ($items->isEmpty()) {
            return false;
        }

        // Get all tag IDs from items
        $itemTagIds = $items->flatMap(function ($item) {
            return $item->tags->pluck('id');
        })->unique()->toArray();

        // Check if there's any intersection between user tags and item tags
        return !empty(array_intersect($userTagIds, $itemTagIds));
    }

    /**
     * Get matching tags between announcement and user
     */
    private function getMatchingTags(Announcement $announcement, array $userTagIds): \Illuminate\Support\Collection
    {
        $items = collect();

        if ($announcement->auction_id && $announcement->auction) {
            if ($announcement->auction->catalogue) {
                $items = $announcement->auction->catalogue->items;
            }
        } elseif ($announcement->catalogue_id && $announcement->catalogue) {
            $items = $announcement->catalogue->items;
        }

        // Get all tags from items
        $allTags = $items->flatMap(function ($item) {
            return $item->tags;
        })->unique('id');

        // Filter to only matching tags
        return $allTags->filter(function ($tag) use ($userTagIds) {
            return in_array($tag->id, $userTagIds);
        });
    }
}
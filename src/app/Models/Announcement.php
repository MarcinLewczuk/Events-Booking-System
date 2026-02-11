<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'topic',
        'auction_id',
        'catalogue_id',
        'created_by',
    ];

    /**
     * Boot method to add model events
     */
    protected static function boot()
    {
        parent::boot();

        // Updated validation: allow both to be null for general announcements
        static::saving(function ($announcement) {
            // Can't be linked to both
            if ($announcement->auction_id && $announcement->catalogue_id) {
                throw new \Exception('Announcement cannot be linked to both auction and catalogue');
            }
            
            // If both are null, it's a general announcement (allowed now)
            // If one is set, topic should match
            if ($announcement->auction_id && $announcement->topic !== 'auction') {
                $announcement->topic = 'auction';
            }
            if ($announcement->catalogue_id && $announcement->topic !== 'catalogue') {
                $announcement->topic = 'catalogue';
            }
            if (!$announcement->auction_id && !$announcement->catalogue_id) {
                $announcement->topic = 'general';
            }
        });
    }

    /**
     * Relation to Auction
     */
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    /**
     * Relation to Catalogue
     */
    public function catalogue()
    {
        return $this->belongsTo(Catalogue::class);
    }

    /**
     * Relation to User who created the announcement
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to filter by topic
     */
    public function scopeTopic($query, $topic)
    {
        return $query->where('topic', $topic);
    }

    /**
     * Scope for general announcements
     */
    public function scopeGeneral($query)
    {
        return $query->whereNull('auction_id')->whereNull('catalogue_id');
    }

    /**
     * Check if announcement is general (not linked to auction or catalogue)
     */
    public function isGeneral()
    {
        return !$this->auction_id && !$this->catalogue_id;
    }
    /**
     * Get the name of the related item (auction or catalogue)
     */
    public function getRelatedItemName()
    {
        if ($this->auction_id && $this->auction) {
            return $this->auction->title;
        }
        
        if ($this->catalogue_id && $this->catalogue) {
            return $this->catalogue->name;
        }
        
        return 'General Announcement';
    }
    /**
     * Generate auto message for auction
     */
    public static function generateAuctionMessage($auctionId)
    {
        $auction = Auction::with(['catalogue.items.tags', 'catalogue.items.category', 'location'])
            ->findOrFail($auctionId);

        $message = "Dear Valued Customer,\n\n";
        $message .= "We are delighted to announce our upcoming auction:\n\n";
        
        // Auction details
        $message .= "📅 Date: " . $auction->auction_date->format('l, F j, Y') . "\n";
        if ($auction->start_time) {
            $message .= "🕐 Time: " . $auction->start_time . "\n";
        }
        if ($auction->location) {
            $message .= "📍 Location: " . $auction->location->name . "\n";
        }
        
        if ($auction->catalogue) {
            $message .= "📚 Catalogue: " . $auction->catalogue->name . "\n";
            
            // Get category/theme
            if ($auction->catalogue->category) {
                $message .= "🎨 Theme: " . $auction->catalogue->category->name . "\n";
            }
            
            // Get top 5 most frequent tags
            $tags = $auction->catalogue->items->pluck('tags')->flatten()->pluck('name');
            $topTags = $tags->countBy()->sortDesc()->take(5)->keys();
            
            if ($topTags->isNotEmpty()) {
                $message .= "🏷️ Featured: " . $topTags->join(', ') . "\n";
            }
            
            $message .= "\n";
            
            // List some items (top 6 by estimated price)
            $items = $auction->catalogue->items->sortByDesc('estimated_price')->take(6);
            
            if ($items->isNotEmpty()) {
                $message .= "Featured Items:\n\n";
                
                foreach ($items as $index => $item) {
                    $itemNumber = $index + 1;
                    $message .= "{$item->title}\n";
                    
                    if ($item->creator) {
                        $message .= "   Artist/Creator: " . $item->creator . "\n";
                    }
                    
                    
                    if ($item->estimated_price) {
                        $message .= "   Estimated Price: £" . number_format($item->estimated_price, 2) . "\n";
                    }
                    
                    $message .= "\n";
                }
            }
            
            $totalItems = $auction->catalogue->items->count();
            if ($totalItems > 6) {
                $message .= "...and " . ($totalItems - 6) . " more exciting items!\n\n";
            }
        }
        
        $message .= "Don't miss this opportunity to acquire exceptional pieces. ";
        $message .= "Visit our website to view the full catalogue and register for bidding.\n\n";
        $message .= "We look forward to seeing you at the auction!\n\n";
        $message .= "Best regards,\n";
        $message .= "The Auction Team";
        
        return $message;
    }
}
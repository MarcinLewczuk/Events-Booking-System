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
        'event_id',
        'created_by',
    ];

    /**
     * Boot method to add model events
     */
    protected static function boot()
    {
        parent::boot();

        // Updated validation: allow all to be null for general announcements
        static::saving(function ($announcement) {
            // Can't be linked to multiple items
            $linkedCount = ($announcement->auction_id ? 1 : 0) + 
                           ($announcement->catalogue_id ? 1 : 0) + 
                           ($announcement->event_id ? 1 : 0);
            
            if ($linkedCount > 1) {
                throw new \Exception('Announcement cannot be linked to multiple items');
            }
            
            // Set topic based on what's linked
            if ($announcement->auction_id) {
                $announcement->topic = 'auction';
            } elseif ($announcement->catalogue_id) {
                $announcement->topic = 'catalogue';
            } elseif ($announcement->event_id) {
                $announcement->topic = 'event';
            } else {
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
     * Relation to Event
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
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
     * Get the name of the related item (auction, catalogue, or event)
     */
    public function getRelatedItemName()
    {
        if ($this->auction_id && $this->auction) {
            return $this->auction->title;
        }
        
        if ($this->catalogue_id && $this->catalogue) {
            return $this->catalogue->name;
        }

        if ($this->event_id && $this->event) {
            return $this->event->title;
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

    /**
     * Generate auto message for event
     */
    public static function generateEventMessage($eventId)
    {
        $event = Event::with(['location', 'tags'])
            ->findOrFail($eventId);

        $message = "Dear Valued Visitor,\n\n";
        $message .= "We're excited to invite you to an upcoming event!\n\n";
        
        // Event details
        $message .= "🎉 Event: " . $event->title . "\n";
        $message .= "📅 Date: " . $event->start_datetime->format('l, F j, Y') . "\n";
        $message .= "🕐 Time: " . $event->start_datetime->format('g:i A') . "\n";
        
        if ($event->location) {
            $message .= "📍 Location: " . $event->location->name . "\n";
        }
        
        if ($event->description) {
            $message .= "\n" . $event->description . "\n";
        }
        
        // Event details
        if ($event->is_paid) {
            $message .= "\n💰 Pricing:\n";
            if ($event->adult_price) {
                $message .= "   Adult: £" . number_format($event->adult_price, 2) . "\n";
            }
            if ($event->child_price) {
                $message .= "   Child: £" . number_format($event->child_price, 2) . "\n";
            }
            if ($event->concession_price) {
                $message .= "   Concession: £" . number_format($event->concession_price, 2) . "\n";
            }
        } else {
            $message .= "\n🎟️ This is a FREE event!\n";
        }
        
        // Show tags
        if ($event->tags && $event->tags->isNotEmpty()) {
            $message .= "\n🏷️ Event Categories: " . $event->tags->pluck('name')->join(', ') . "\n";
        }
        
        // Capacity info
        if ($event->capacity) {
            $bookedTickets = $event->booked_tickets ?? 0;
            $remaining = max(0, $event->capacity - $bookedTickets);
            $message .= "\n🎫 Tickets Available: " . $remaining . " of " . $event->capacity . "\n";
        }
        
        $message .= "\nWe look forward to welcoming you!\n\n";
        $message .= "For more information or to book tickets, visit our website.\n\n";
        $message .= "Best regards,\n";
        $message .= "The Events Team";
        
        return $message;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id',
        'item_id',
        'price',
        'sale_status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Relationships
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function settlement()
    {
        return $this->hasOne(Settlement::class);
    }

    // Helper methods
    public function getHighestBid()
    {
        return $this->bids()->orderBy('bid_amount', 'desc')->first();
    }

    public function isSold(): bool
    {
        return $this->sale_status === 'sold';
    }

    /**
     * Get lot number from the catalogue_items pivot
     */
    public function getLotNumber()
    {
        $catalogueId = $this->auction->catalogue_id;
        $catalogueItem = $this->item->catalogues()
            ->where('catalogue_id', $catalogueId)
            ->first();
        
        return $catalogueItem ? $catalogueItem->pivot->lot_number : null;
    }

    /**
     * Get lot reference number from the catalogue_items pivot
     */
    public function getLotReferenceNumber()
    {
        $catalogueId = $this->auction->catalogue_id;
        $catalogueItem = $this->item->catalogues()
            ->where('catalogue_id', $catalogueId)
            ->first();
        
        return $catalogueItem ? $catalogueItem->pivot->lot_reference_number : null;
    }
}
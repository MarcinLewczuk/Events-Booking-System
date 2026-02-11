<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_item_id',
        'auction_customer_id',
        'bid_amount',
        'bid_type',
    ];

    protected $casts = [
        'bid_amount' => 'decimal:2',
    ];

    // Relationships
    public function auctionItem()
    {
        return $this->belongsTo(AuctionItem::class);
    }

    public function auctionCustomer()
    {
        return $this->belongsTo(AuctionCustomer::class);
    }

    // Convenience accessor for the bidder
    public function getBidderAttribute()
    {
        return $this->auctionCustomer->customer;
    }
}
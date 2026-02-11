<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionCustomer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'auction_id',
        'customer_id',
        'bidder_number',
        'registered_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    // Relationships
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
}
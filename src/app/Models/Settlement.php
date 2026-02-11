<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_item_id',
        'buyer_customer_id',
        'seller_customer_id',
        'hammer_price',
        'commission',
        'total_due',
        'payment_status',
        'settlement_date',
    ];

    protected $casts = [
        'hammer_price' => 'decimal:2',
        'commission' => 'decimal:2',
        'total_due' => 'decimal:2',
        'settlement_date' => 'date',
    ];

    // Relationships
    public function auctionItem()
    {
        return $this->belongsTo(AuctionItem::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_customer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_customer_id');
    }

    // Helper methods
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isOverdue(): bool
    {
        return $this->payment_status === 'overdue';
    }
}
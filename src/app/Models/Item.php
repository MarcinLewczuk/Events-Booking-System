<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'title',
        'short_description',
        'detailed_description',
        'creator',
        'dimensions',
        'year_of_creation',
        'weight',
        'estimated_price',
        'reserve_price',
        'withdrawal_fee',
        'category_id',
        'band_id',
        'location_id',
        'status',
        'intake_tier',
        'priority_flag',
        'primary_image_id',
        'current_stage_entered_at',
        'created_by',
        'approved_by',
        'approval_date',
    ];

    protected $casts = [
        'estimated_price' => 'decimal:2',
        'reserve_price' => 'decimal:2',
        'withdrawal_fee' => 'decimal:2',
        'weight' => 'decimal:2',
        'year_of_creation' => 'integer',
        'priority_flag' => 'boolean',
        'current_stage_entered_at' => 'datetime',
        'approval_date' => 'datetime',
    ];

    // Relationships

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function band()
    {
        return $this->belongsTo(Band::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function primaryImage()
    {
        return $this->belongsTo(ItemImage::class, 'primary_image_id');
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function catalogues()
    {
        return $this->belongsToMany(Catalogue::class, 'catalogue_items')
                    ->withPivot('lot_number', 'display_order', 'title_override', 'description_override');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'item_tags');
    }

    public function auctionItems()
    {
        return $this->hasMany(AuctionItem::class);
    }

    public function auctions()
    {
        return $this->belongsToMany(Auction::class, 'auction_items')
            ->withPivot('price', 'sale_status')
            ->withTimestamps();
    }
}
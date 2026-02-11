<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'status',
        'created_by',
    ];

    // Relation to Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relation to User who created the catalogue
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relation to Items in the catalogue (many-to-many)
    public function items()
    {
        return $this->belongsToMany(Item::class, 'catalogue_items')
            ->withPivot([
                'lot_number',
                'display_order',
                'title_override',
                'description_override',
            ])
            ->orderBy('catalogue_items.display_order');
    }

    // Relation to Auctions
    public function auctions()
    {
        return $this->hasMany(Auction::class);
    }

    /**
     * Check if catalogue can be submitted for approval
     */
    public function canBeSubmittedForApproval(): bool
    {
        return $this->status === 'draft' && $this->items()->count() > 0;
    }

    /**
     * Check if catalogue can be approved
     */
    public function canBeApproved(): bool
    {
        return $this->status === 'awaiting_approval';
    }
}
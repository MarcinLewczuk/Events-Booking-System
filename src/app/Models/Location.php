<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'description',
        'max_attendance',
        'seating_rows',
        'seating_columns',
        'disabled_seats',
        'image_path',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'disabled_seats' => 'array',
    ];

    // Auctions at this location
    public function auctions()
    {
        return $this->hasMany(Auction::class);
    }

    // Events at this location
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Items at this location
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    // Get upcoming auctions only
    public function upcomingAuctions()
    {
        return $this->auctions()
            ->where('approval_status', 'approved')
            ->where('auction_date', '>=', now())
            ->orderBy('auction_date', 'asc');
    }

    // Get most valuable items at this location
    public function valuableItems($limit = 6)
    {
        return $this->items()
            ->where('status', 'approved')
            ->orderBy('estimated_price', 'desc')
            ->limit($limit);
    }

    // Get image URL
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return null;
    }

    // Delete image file when location is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($location) {
            if ($location->image_path) {
                Storage::disk('public')->delete($location->image_path);
            }
        });
    }

    /**
     * Get all seat labels for this location
     */
    public function getAllSeats()
    {
        $seats = [];
        $rows = range('A', chr(ord('A') + $this->seating_rows - 1));
        
        foreach ($rows as $row) {
            for ($col = 1; $col <= $this->seating_columns; $col++) {
                $seatNumber = $row . $col;
                if (!in_array($seatNumber, $this->disabled_seats ?? [])) {
                    $seats[] = $seatNumber;
                }
            }
        }
        
        return $seats;
    }

    /**
     * Get available seats count for location
     */
    public function getAvailableSeatsCount()
    {
        return count($this->getAllSeats());
    }
}
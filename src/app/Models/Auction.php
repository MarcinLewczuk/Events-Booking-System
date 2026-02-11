<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'catalogue_id',
        'title',
        'auction_date',
        'start_time',
        'status',
        'approval_status',
        'approved_by',
        'approval_status_changed_at',
        'rejection_reason',
        'created_by',
        'location_id',
        'auction_block',
    ];

    protected $casts = [
        'auction_date' => 'date',
        'approval_status_changed_at' => 'datetime',
    ];

    public function catalogue()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function auctionCustomers()
    {
        return $this->hasMany(AuctionCustomer::class);
    }

    public function auctionItems()
    {
        return $this->hasMany(AuctionItem::class);
    }

    public function registeredCustomers()
    {
        return $this->belongsToMany(User::class, 'auction_customers', 'auction_id', 'customer_id')
            ->withPivot('bidder_number', 'registered_at');
    }

    /**
     * Check if auction can be edited
     */
    public function canBeEdited(): bool
    {
        return !in_array($this->status, ['open', 'closed', 'settled']);
    }

    /**
     * Check if auction can be approved
     */
    public function canBeApproved(): bool
    {
        return $this->approval_status === 'awaiting_approval';
    }

    /**
     * Check if auction is approved
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }
    
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    /**
     * Seat bookings for this auction
     */
    public function seatBookings()
    {
        return $this->hasMany(SeatBooking::class);
    }

    /**
     * Get booked seats for this auction
     */
    public function getBookedSeats()
    {
        return $this->seatBookings()->active()->pluck('seat_number')->toArray();
    }

    /**
     * Get available seats count
     */
    public function getAvailableSeatsCount()
    {
        if (!$this->location) {
            return 0;
        }
        
        $totalSeats = $this->location->getAvailableSeatsCount();
        $bookedSeats = $this->seatBookings()->active()->count();
        
        return $totalSeats - $bookedSeats;
    }

    /**
     * Check if user has booked a seat
     */
    public function hasUserBookedSeat($userId)
    {
        return $this->seatBookings()
            ->where('user_id', $userId)
            ->active()
            ->exists();
    }

    /**
     * Get user's booked seat
     */
    public function getUserBookedSeat($userId)
    {
        return $this->seatBookings()
            ->where('user_id', $userId)
            ->active()
            ->first();
    }
}
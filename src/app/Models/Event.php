<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'itinerary',
        'start_datetime',
        'end_datetime',
        'duration_minutes',
        'location_id',
        'category_id',
        'capacity',
        'is_paid',
        'adult_price',
        'child_price',
        'concession_price',
        'primary_image',
        'gallery_images',
        'status',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'is_paid' => 'boolean',
        'adult_price' => 'decimal:2',
        'child_price' => 'decimal:2',
        'concession_price' => 'decimal:2',
        'gallery_images' => 'array',
    ];

    /**
     * Relationships
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function bookings()
    {
        return $this->hasMany(EventBooking::class);
    }

    public function confirmedBookings()
    {
        return $this->hasMany(EventBooking::class)->where('status', 'confirmed');
    }

    /**
     * Computed properties
     */
    public function getBookedTicketsAttribute()
    {
        return $this->confirmedBookings()->sum('total_tickets');
    }

    public function getRemainingSpacesAttribute()
    {
        return max(0, $this->capacity - $this->getBookedTicketsAttribute());
    }

    public function getIsFullyBookedAttribute()
    {
        return $this->getRemainingSpacesAttribute() <= 0;
    }

    public function getIsCancelledAttribute()
    {
        return $this->status === 'cancelled';
    }

    public function getIsUpcomingAttribute()
    {
        return $this->start_datetime > now() && $this->status === 'active';
    }

    public function getCanCancelBookingsAttribute()
    {
        // Can cancel up to 24 hours before event
        return $this->start_datetime->subHours(24) > now();
    }

    /**
     * Scopes
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>', now())
                     ->where('status', 'active')
                     ->orderBy('start_datetime', 'asc');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if a user/email has already booked this event
     */
    public function hasBooking($userIdOrEmail)
    {
        if (is_numeric($userIdOrEmail)) {
            return $this->bookings()
                        ->where('user_id', $userIdOrEmail)
                        ->where('status', 'confirmed')
                        ->exists();
        }

        return $this->bookings()
                    ->where('guest_email', $userIdOrEmail)
                    ->where('status', 'confirmed')
                    ->exists();
    }

    /**
     * Check if event can accommodate requested tickets
     */
    public function canAccommodate($ticketCount)
    {
        return $this->getRemainingSpacesAttribute() >= $ticketCount;
    }
}

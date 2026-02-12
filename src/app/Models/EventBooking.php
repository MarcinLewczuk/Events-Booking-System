<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventBooking extends Model
{
    protected $fillable = [
        'booking_reference',
        'event_id',
        'user_id',
        'guest_first_name',
        'guest_surname',
        'guest_email',
        'adult_tickets',
        'child_tickets',
        'concession_tickets',
        'total_tickets',
        'total_amount',
        'accessibility_notes',
        'newsletter_opt_in',
        'status',
        'cancelled_at',
        'refund_amount',
        'cancellation_reason',
        'confirmation_email_sent',
        'week_reminder_sent',
        'day_reminder_sent',
    ];

    protected $casts = [
        'newsletter_opt_in' => 'boolean',
        'cancelled_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'confirmation_email_sent' => 'boolean',
        'week_reminder_sent' => 'boolean',
        'day_reminder_sent' => 'boolean',
    ];

    /**
     * Boot method to generate booking reference
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (!$booking->booking_reference) {
                $booking->booking_reference = static::generateBookingReference();
            }
        });
    }

    /**
     * Generate unique booking reference
     */
    public static function generateBookingReference()
    {
        do {
            $reference = 'DLA-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (static::where('booking_reference', $reference)->exists());

        return $reference;
    }

    /**
     * Relationships
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Computed properties
     */
    public function getAttendeeNameAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }
        
        if ($this->guest_first_name && $this->guest_surname) {
            return $this->guest_first_name . ' ' . $this->guest_surname;
        }
        
        return $this->guest_first_name ?? 'Guest';
    }

    public function getAttendeeEmailAttribute()
    {
        return $this->user ? $this->user->email : $this->guest_email;
    }

    public function getIsGuestBookingAttribute()
    {
        return is_null($this->user_id);
    }

    public function getCanBeCancelledAttribute()
    {
        if ($this->status !== 'confirmed') {
            return false;
        }

        // Can cancel up to 24 hours before event
        return $this->event->start_datetime->subHours(24) > now();
    }

    public function getRefundPercentageAttribute()
    {
        if (!$this->can_be_cancelled) {
            return 0;
        }

        $hoursUntilEvent = now()->diffInHours($this->event->start_datetime, false);

        // More than 24 hours: 100% refund
        if ($hoursUntilEvent > 24) {
            return 100;
        }

        // Less than 24 hours: 50% refund
        return 50;
    }

    /**
     * Cancel booking with refund calculation
     */
    public function cancel($reason = null)
    {
        if (!$this->can_be_cancelled) {
            throw new \Exception('This booking cannot be cancelled.');
        }

        $refundPercentage = $this->getRefundPercentageAttribute();
        $refundAmount = ($this->total_amount * $refundPercentage) / 100;

        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'refund_amount' => $refundAmount,
            'cancellation_reason' => $reason,
        ]);

        // TODO: Trigger cancellation email
        // Queue job or fire event here

        return $refundAmount;
    }

    /**
     * Scopes
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}

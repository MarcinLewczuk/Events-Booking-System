<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_reference',
        'event_booking_id',
        'event_id',
        'type',
        'price',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (!$ticket->ticket_reference) {
                $ticket->ticket_reference = static::generateReference();
            }
        });
    }

    public static function generateReference(): string
    {
        do {
            $reference = 'TKT-' . strtoupper(Str::random(8));
        } while (static::where('ticket_reference', $reference)->exists());

        return $reference;
    }

    public function booking()
    {
        return $this->belongsTo(EventBooking::class, 'event_booking_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return ucfirst($this->type);
    }

    public function scopeValid($query)
    {
        return $query->where('status', 'valid');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Create tickets for a booking
     */
    public static function createForBooking(EventBooking $booking): void
    {
        $event = $booking->event;
        $tickets = [];

        for ($i = 0; $i < $booking->adult_tickets; $i++) {
            $tickets[] = [
                'event_booking_id' => $booking->id,
                'event_id' => $booking->event_id,
                'type' => 'adult',
                'price' => $event->adult_price ?? 0,
            ];
        }

        for ($i = 0; $i < $booking->child_tickets; $i++) {
            $tickets[] = [
                'event_booking_id' => $booking->id,
                'event_id' => $booking->event_id,
                'type' => 'child',
                'price' => $event->child_price ?? 0,
            ];
        }

        for ($i = 0; $i < $booking->concession_tickets; $i++) {
            $tickets[] = [
                'event_booking_id' => $booking->id,
                'event_id' => $booking->event_id,
                'type' => 'concession',
                'price' => $event->concession_price ?? 0,
            ];
        }

        foreach ($tickets as $ticketData) {
            static::create($ticketData);
        }
    }
}

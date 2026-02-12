<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'title',
        'first_name',
        'surname',
        'email',
        'password',
        'role',
        'contact_telephone_number',
        'contact_address',
        'buyer_approved_status',
        'newsletter_consent',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'buyer_approved_status' => 'boolean',
        'newsletter_consent' => 'boolean',
    ];

    // Full name accessor
    public function getFullNameAttribute(): string
    {
        return trim("{$this->title} {$this->first_name} {$this->surname}");
    }

    // Relationship: User's interested tags
    public function interestedTags()
    {
        return $this->belongsToMany(Tag::class, 'user_tags')->withTimestamps();
    }

    // Helper: Check if user is interested in a tag
    public function isInterestedIn($tagId): bool
    {
        return $this->interestedTags()->where('tag_id', $tagId)->exists();
    }

    // Helper: Toggle interest in a tag
    public function toggleTagInterest($tagId): void
    {
        if ($this->isInterestedIn($tagId)) {
            $this->interestedTags()->detach($tagId);
        } else {
            $this->interestedTags()->attach($tagId);
        }
    }

    /**
     * Seat bookings by this user
     */
    public function seatBookings()
    {
        return $this->hasMany(SeatBooking::class);
    }
}
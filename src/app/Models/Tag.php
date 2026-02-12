<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    // Relationship: Items with this tag
    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_tags');
    }

    // Relationship: Users interested in this tag
    public function interestedUsers()
    {
        return $this->belongsToMany(User::class, 'user_tags')->withTimestamps();
    }

    // Relationship: Events with this tag
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_tags');
    }
}
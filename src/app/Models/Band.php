<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Band extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'min_price',
        'max_price',
        'requires_approval'
    ];

    // Optional: relation to Items
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}

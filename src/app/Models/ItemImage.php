<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    protected $fillable = [
        'item_id',
        'path',
        'thumb_path',
        'display_order',
        'uploaded_by',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

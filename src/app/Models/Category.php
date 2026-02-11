<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
    ];

    // Parent category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Child categories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Catalogues linked to this category
    public function catalogues()
    {
        return $this->hasMany(Catalogue::class, 'category_id');
    }

    // Items linked to this category
    public function items()
    {
        return $this->hasMany(Item::class, 'category_id');
    }
}
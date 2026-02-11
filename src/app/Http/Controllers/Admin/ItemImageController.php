<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemImageController extends Controller
{
    public function store(Request $request, Item $item)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'primary' => 'nullable|integer',
        ]);

        foreach ($request->file('images') as $index => $file) {
            $path = $file->store("items/{$item->id}", 'public');

            $image = ItemImage::create([
                'item_id' => $item->id,
                'path' => $path,
                'display_order' => $index + 1,
                'uploaded_by' => auth()->id(),
            ]);

            if ((int)$request->primary === $index) {
                $item->update(['primary_image_id' => $image->id]);
            }
        }

        return back()->with('success', 'Images uploaded');
    }
}

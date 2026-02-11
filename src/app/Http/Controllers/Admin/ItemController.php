<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Catalogue;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Band;
use App\Models\ItemImage;
use App\Models\Tag;
use App\Models\Location;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff']);
    }

    /**
     * Display a listing of the items with filters and search.
     */
    public function index(Request $request)
    {
        $query = Item::query()->with(['category', 'primaryImage']);

        // Global search (title + descriptions)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('detailed_description', 'like', "%{$search}%");
            });
        }

        // Filter: status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter: category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter: intake tier
        if ($request->filled('intake_tier')) {
            $query->where('intake_tier', $request->intake_tier);
        }

        // Filter: priority
        if ($request->filled('priority_flag')) {
            $query->where('priority_flag', $request->priority_flag);
        }
        // Filter by year of creation range
        if ($request->filled('year_from')) {
            $query->where('year_of_creation', '>=', $request->year_from);
        }
        if ($request->filled('year_to')) {
            $query->where('year_of_creation', '<=', $request->year_to);
        }

        // Filter by weight range
        if ($request->filled('weight_from')) {
            $query->where('weight', '>=', $request->weight_from);
        }
        if ($request->filled('weight_to')) {
            $query->where('weight', '<=', $request->weight_to);
        }
        // Filter: search by title, description, or creator
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('short_description', 'like', "%{$search}%")
                ->orWhere('creator', 'like', "%{$search}%");
            });
        }
        $items = $query->orderBy('created_at', 'desc')
                       ->paginate(25)
                       ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.items.index', compact('items', 'categories'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $bands = Band::orderBy('min_price')->get();
        $tags = Tag::orderBy('name')->get();
        $customers = User::where('role', 'customer')->orderBy('first_name')->get();
        $locations = Location::orderBy('name')->get();

        return view('admin.items.create', compact('categories', 'bands', 'tags', 'customers', 'locations'));
    }

    /**
     * Show the form for editing an item.
     */

    public function edit(Item $item)
    {
        $item->load(['category', 'band', 'tags', 'images', 'primaryImage']);
        
        $categories = Category::orderBy('name')->get();
        $bands = Band::orderBy('min_price')->get();
        $tags = Tag::orderBy('name')->get();
        $customers = User::where('role', 'customer')->orderBy('first_name')->get();
        $locations = Location::orderBy('name')->get();

        return view('admin.items.edit', compact('item', 'categories', 'bands', 'tags', 'customers', 'locations'));
    }


    public function update(Request $request, Item $item)
    {
    $validated = $request->validate([
        'customer_id' => 'nullable|exists:users,id',
        'title' => 'required|string|max:255',
        'short_desscription' => 'nullable|string|max:500',
        'detailed_description' => 'nullable|string',
        'creator' => 'nullable|string|max:255',
        'dimensions' => 'nullable|string|max:255',
        'year_of_creation' => 'nullable|integer|min:1|max:9999',
        'weight' => 'nullable|numeric|min:0|max:99999.99',
        'estimated_price' => 'nullable|numeric|min:0',
        'reserve_price' => 'nullable|numeric|min:0',
        'withdrawal_fee' => 'nullable|numeric|min:0',
        'category_id' => 'nullable|exists:categories,id',
        'band_id' => 'nullable|exists:bands,id',
        'location_id' => 'nullable|exists:locations,id',
        'status' => 'required|in:intake,photos,description,catalogue_ready,awaiting_approval,published',
        'intake_tier' => 'required|in:general,featured',
        'priority_flag' => 'boolean',
        'primary_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'tags' => 'nullable|array',
        'tags.*' => 'exists:tags,id',
    ]);

   $updateData = [
        'customer_id' => $validated['customer_id'] ?? null,
        'title' => $validated['title'],
        'short_description' => $validated['short_description'] ?? null,
        'detailed_description' => $validated['detailed_description'] ?? null,
        'creator' => $validated['creator'] ?? null,
        'dimensions' => $validated['dimensions'] ?? null,
        'year_of_creation' => $validated['year_of_creation'] ?? null,
        'weight' => $validated['weight'] ?? null,
        'estimated_price' => $validated['estimated_price'] ?? null,
        'reserve_price' => $validated['reserve_price'] ?? null,
        'withdrawal_fee' => $validated['withdrawal_fee'] ?? null,
        'category_id' => $validated['category_id'] ?? null,
        'band_id' => $validated['band_id'] ?? null,
        'location_id' => $validated['location_id'] ?? null,
        'status' => $validated['status'],
        'intake_tier' => $validated['intake_tier'],
        'priority_flag' => $validated['priority_flag'] ?? false,
        'updated_by' => auth()->id(),
    ];

    // Allow status change if provided (but not to 'published' - that requires approval)
    if ($request->filled('status') && $request->status !== 'published') {
        $updateData['status'] = $request->status;
        $updateData['current_stage_entered_at'] = now();
    }

    $item->update($updateData);

        // Handle primary image replacement
        if ($request->hasFile('primary_image')) {
            $path = $request->file('primary_image')->store('items', 'public');
            $image = ItemImage::create([
                'item_id' => $item->id,
                'path' => $path,
                'uploaded_by' => auth()->id(),
            ]);
            $item->update(['primary_image_id' => $image->id]);
        }

        // Handle additional images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('items', 'public');
                ItemImage::create([
                    'item_id' => $item->id,
                    'path' => $path,
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }

        // Sync tags
        if ($request->has('tags')) {
            $item->tags()->sync($request->tags);
        } else {
            $item->tags()->sync([]);
        }

        return redirect()->route('admin.items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Store a newly created item in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'detailed_description' => 'nullable|string',
            'creator' => 'nullable|string|max:255',
            'dimensions' => 'nullable|string|max:255',
            'year_of_creation' => 'nullable|integer|min:1|max:' . date('Y'),
            'weight' => 'nullable|numeric|min:0|max:99999.99',
            'estimated_price' => 'nullable|numeric|min:0',
            'reserve_price' => 'nullable|numeric|min:0',
            'withdrawal_fee' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'band_id' => 'nullable|exists:bands,id',
            'location_id' => 'nullable|exists:locations,id',
            'status' => 'required|in:intake,photos,description,catalogue_ready,awaiting_approval,published',
            'intake_tier' => 'required|in:general,featured',
            'priority_flag' => 'boolean',
            'primary_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $item = Item::create([
            'customer_id' => $validated['customer_id'] ?? null,
            'title' => $validated['title'],
            'short_description' => $validated['short_description'] ?? null,
            'detailed_description' => $validated['detailed_description'] ?? null,
            'creator' => $validated['creator'] ?? null,
            'dimensions' => $validated['dimensions'] ?? null,
            'year_of_creation' => $validated['year_of_creation'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'estimated_price' => $validated['estimated_price'] ?? null,
            'reserve_price' => $validated['reserve_price'] ?? null,
            'withdrawal_fee' => $validated['withdrawal_fee'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'band_id' => $validated['band_id'] ?? null,
            'location_id' => $validated['location_id'] ?? null,
            'status' => $validated['status'],
            'intake_tier' => $validated['intake_tier'],
            'priority_flag' => $validated['priority_flag'] ?? false,
            'current_stage_entered_at' => now(),
            'created_by' => auth()->id(),
        ]);

        // Handle primary image upload
        if ($request->hasFile('primary_image')) {
            $path = $request->file('primary_image')->store('items', 'public');
            $image = ItemImage::create([
                'item_id' => $item->id,
                'path' => $path,
                'uploaded_by' => auth()->id(),
            ]);
            $item->update(['primary_image_id' => $image->id]);
        }

        // Handle additional images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('items', 'public');
                ItemImage::create([
                    'item_id' => $item->id,
                    'path' => $path,
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }

        // Sync tags
        if ($request->has('tags')) {
            $item->tags()->sync($request->tags);
        }

        return redirect()->route('admin.items.index')->with('success', 'Item created successfully.');
    }
    /**
     * Many-to-many relation with catalogues.
     */
    public function catalogues()
    {
        return $this->belongsToMany(Catalogue::class, 'catalogue_items')
                    ->withPivot('lot_number', 'display_order', 'title_override', 'description_override');
    }

    public function destroy(Item $item)
    {
        // Delete all associated images from storage and database
        foreach ($item->images as $image) {
            if (\Storage::disk('public')->exists($image->path)) {
                \Storage::disk('public')->delete($image->path);
            }
            $image->delete();
        }

        // Remove any links in the catalogue_items pivot table
        $item->catalogues()->detach();

        // Delete the item itself
        $item->delete();

        return redirect()->route('admin.items.index')
                        ->with('success', 'Item and its catalogue assignments deleted successfully.');
    }

    public function submitForApproval(Item $item)
    {
        // Only staff, approver, admin can submit
        if (!in_array(auth()->user()->role, ['admin', 'staff', 'approver'])) {
            abort(403);
        }

        if ($item->status !== 'draft') {
            return back()->withErrors(['error' => 'Only draft items can be submitted for approval.']);
        }

        $item->update([
            'status' => 'awaiting_approval',
            'current_stage_entered_at' => now(),
        ]);

        return back()->with('success', 'Item submitted for approval.');
    }

    /**
     * Approve item (only admin/approver)
     */
    public function approve(Item $item)
    {
        // Middleware handles role check, but double-check
        if (!in_array(auth()->user()->role, ['admin', 'approver'])) {
            abort(403, 'Only admins and approvers can approve items.');
        }

        if ($item->status !== 'awaiting_approval') {
            return back()->withErrors(['error' => 'Only items awaiting approval can be approved.']);
        }

        $item->update([
            'status' => 'published',  // Changed from 'approved' to 'published'
            'approved_by' => auth()->id(),
            'current_stage_entered_at' => now(),
        ]);

        return back()->with('success', 'Item approved and published successfully.');
    }

    /**
     * Reject item (only admin/approver)
     */
    public function reject(Request $request, Item $item)
    {
        // Middleware handles role check
        if (!in_array(auth()->user()->role, ['admin', 'approver'])) {
            abort(403, 'Only admins and approvers can reject items.');
        }

        if ($item->status !== 'awaiting_approval') {
            return back()->withErrors(['error' => 'Only items awaiting approval can be rejected.']);
        }

        $item->update([
            'status' => 'draft', // Send back to draft
            'approved_by' => null,
            'current_stage_entered_at' => now(),
        ]);

        return back()->with('success', 'Item rejected and sent back to draft.');
    }

    /**
     * Delete an item image
     */
    public function deleteImage(Item $item, ItemImage $image)
    {
        // Check if the image belongs to the item
        if ($image->item_id !== $item->id) {
            abort(404);
        }

        // Don't allow deleting the primary image this way
        if ($item->primary_image_id === $image->id) {
            return back()->withErrors(['error' => 'Cannot delete the primary image. Set a different primary image first.']);
        }

        // Delete the file from storage
        if (Storage::exists($image->path)) {
            Storage::delete($image->path);
        }

        // Delete the database record
        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }
}
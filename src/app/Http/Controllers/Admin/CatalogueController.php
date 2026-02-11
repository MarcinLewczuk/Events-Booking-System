<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');
    }

    // List all catalogues
    public function index()
    {
        $catalogues = Catalogue::with('category', 'creator')->paginate(12);
        return view('admin.catalogues.index', compact('catalogues'));
    }

    // Show form to create catalogue
    public function create()
    {
        $categories = Category::all();
        return view('admin.catalogues.create', compact('categories'));
    }

    // Store a new catalogue
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'status' => 'nullable|in:draft,awaiting_approval',
        ]);

        $status = $request->status ?? 'draft';
        
        // Users cannot set status to published directly
        if ($status === 'published') {
            $status = 'draft';
        }

        Catalogue::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'status' => $status,
            'created_by' => auth()->id()
        ]);

        return redirect()->route('admin.catalogues.index')->with('success', 'Catalogue created.');
    }

    // Edit catalogue and manage items
    public function edit(Catalogue $catalogue)
    {
        $categories = Category::all();

        // Items already in this catalogue
        $items = $catalogue->items()->with('band')->get();

        // Get category IDs to include: catalogue category + its children (subcategories)
        $categoryIds = [$catalogue->category_id];
        $subcategories = Category::where('parent_id', $catalogue->category_id)->pluck('id');
        $categoryIds = array_merge($categoryIds, $subcategories->toArray());

        // Items NOT in catalogue AND matching catalogue category OR subcategories
        $availableItems = Item::whereIn('category_id', $categoryIds)
            ->whereNotIn('id', $items->pluck('id'))
            ->get();

        return view('admin.catalogues.edit', compact(
            'catalogue',
            'categories',
            'items',
            'availableItems'
        ));
    }


    // Update catalogue info
    public function update(Request $request, Catalogue $catalogue)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'status' => 'nullable|in:draft,awaiting_approval',
        ]);

        $updateData = [
            'name' => $request->name,
            'category_id' => $request->category_id,
        ];

        // Only update status if provided and not trying to set to published
        if ($request->filled('status') && $request->status !== 'published') {
            $updateData['status'] = $request->status;
        }

        $catalogue->update($updateData);

        return back()->with('success', 'Catalogue updated.');
    }

    // Add item to catalogue
    public function addItem(Request $request, Catalogue $catalogue)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'lot_number' => 'nullable|integer',
        ]);

        // Check if catalogue already has 90 items
        if ($catalogue->items()->count() >= 90) {
            return back()->withErrors(['error' => 'Catalogue is full. Maximum 90 items allowed.']);
        }

        // Check if item already in catalogue
        if ($catalogue->items()->where('item_id', $request->item_id)->exists()) {
            return back()->withErrors(['error' => 'Item is already in this catalogue.']);
        }

        // Get next display order
        $maxOrder = $catalogue->items()->max('catalogue_items.display_order') ?? 0;

        $catalogue->items()->attach($request->item_id, [
            'lot_number' => $request->lot_number,
            'display_order' => $maxOrder + 1,
        ]);

        return back()->with('success', 'Item added to catalogue.');
    }


    // Remove item from catalogue
    public function removeItem(Catalogue $catalogue, Item $item)
    {
        $catalogue->items()->detach($item->id);
        return back()->with('success', 'Item removed from catalogue.');
    }

    // Delete catalogue
    public function destroy(Catalogue $catalogue)
    {
        $catalogue->items()->detach();
        $catalogue->delete();
        return redirect()->route('admin.catalogues.index')->with('success', 'Catalogue deleted.');
    }

    /**
     * Submit catalogue for approval
     */
    public function submitForApproval(Catalogue $catalogue)
    {
        // Check if user owns this catalogue or is admin
        if ($catalogue->created_by !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        if (!$catalogue->canBeSubmittedForApproval()) {
            return back()->withErrors(['error' => 'Catalogue cannot be submitted for approval. Ensure it has items and is in draft status.']);
        }

        $catalogue->update([
            'status' => 'awaiting_approval',
        ]);

        return back()->with('success', 'Catalogue submitted for approval.');
    }

    /**
     * Approve catalogue (Admin/Approver only)
     */
    public function approve(Catalogue $catalogue)
    {
        // Check approval permission
        if (!in_array(auth()->user()->role, ['admin', 'approver'])) {
            abort(403, 'Unauthorized action.');
        }

        if (!$catalogue->canBeApproved()) {
            return back()->withErrors(['error' => 'Catalogue is not awaiting approval.']);
        }

        $catalogue->update([
            'status' => 'published',
        ]);

        return back()->with('success', 'Catalogue approved successfully.');
    }

    /**
     * Reject catalogue (Admin/Approver only)
     */
    public function reject(Request $request, Catalogue $catalogue)
    {
        // Check approval permission
        if (!in_array(auth()->user()->role, ['admin', 'approver'])) {
            abort(403, 'Unauthorized action.');
        }

        if (!$catalogue->canBeApproved()) {
            return back()->withErrors(['error' => 'Catalogue is not awaiting approval.']);
        }

        $catalogue->update([
            'status' => 'draft',
        ]);

        return back()->with('success', 'Catalogue rejected. It has been returned to draft status.');
    }
}
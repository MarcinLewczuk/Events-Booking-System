<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Band;
use Illuminate\Http\Request;

class BandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $query = Band::query()->withCount('items');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $bands = $query->orderBy('min_price')->paginate(25)->withQueryString();

        return view('admin.bands.index', compact('bands'));
    }

    public function create()
    {
        return view('admin.bands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:bands,name',
            'description' => 'nullable|string',
            'min_price' => 'required|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gte:min_price',
            'requires_approval' => 'nullable|boolean',
        ]);

        Band::create([
            'name' => $request->name,
            'description' => $request->description,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'requires_approval' => $request->boolean('requires_approval'),
        ]);

        return redirect()->route('admin.bands.index')
            ->with('success', 'Band created successfully.');
    }

    public function edit(Band $band)
    {
        return view('admin.bands.edit', compact('band'));
    }

    public function update(Request $request, Band $band)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:bands,name,' . $band->id,
            'description' => 'nullable|string',
            'min_price' => 'required|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gte:min_price',
            'requires_approval' => 'nullable|boolean',
        ]);

        $band->update([
            'name' => $request->name,
            'description' => $request->description,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'requires_approval' => $request->boolean('requires_approval'),
        ]);

        return redirect()->route('admin.bands.index')
            ->with('success', 'Band updated successfully.');
    }

    public function destroy(Band $band)
    {
        if ($band->items()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete band assigned to items.']);
        }

        $band->delete();

        return redirect()->route('admin.bands.index')
            ->with('success', 'Band deleted successfully.');
    }
}
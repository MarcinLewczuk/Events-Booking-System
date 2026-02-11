<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $query = Location::query()->withCount('auctions');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $locations = $query->orderBy('name')->paginate(25)->withQueryString();

        return view('admin.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'max_attendance' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'seating_rows' => 'nullable|integer|min:1|max:26',
            'seating_columns' => 'nullable|integer|min:1|max:100',
        ]);

        $data = $request->only('name', 'address', 'description', 'max_attendance', 'seating_rows', 'seating_columns');

        // Validate that max_attendance matches seating capacity if both are provided
        if ($request->filled('seating_rows') && $request->filled('seating_columns') && $request->filled('max_attendance')) {
            $calculatedCapacity = $request->seating_rows * $request->seating_columns;
            
            if ($request->max_attendance != $calculatedCapacity) {
                return back()->withErrors([
                    'max_attendance' => "Maximum attendance ({$request->max_attendance}) must equal rows × columns ({$calculatedCapacity})"
                ])->withInput();
            }
        }
        
        // Auto-calculate max_attendance if seating is configured but max_attendance is not
        if ($request->filled('seating_rows') && $request->filled('seating_columns') && !$request->filled('max_attendance')) {
            $data['max_attendance'] = $request->seating_rows * $request->seating_columns;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'location_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('locations', $imageName, 'public');
            $data['image_path'] = $imagePath;
        }

        Location::create($data);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location created successfully.');
    }

    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'max_attendance' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'seating_rows' => 'nullable|integer|min:1|max:26',
            'seating_columns' => 'nullable|integer|min:1|max:100',
        ]);

        $data = $request->only('name', 'address', 'description', 'max_attendance', 'seating_rows', 'seating_columns');

        // Validate that max_attendance matches seating capacity if both are provided
        if ($request->filled('seating_rows') && $request->filled('seating_columns') && $request->filled('max_attendance')) {
            $calculatedCapacity = $request->seating_rows * $request->seating_columns;
            
            if ($request->max_attendance != $calculatedCapacity) {
                return back()->withErrors([
                    'max_attendance' => "Maximum attendance ({$request->max_attendance}) must equal rows × columns ({$calculatedCapacity})"
                ])->withInput();
            }
        }
        
        // Auto-calculate max_attendance if seating is configured but max_attendance is not
        if ($request->filled('seating_rows') && $request->filled('seating_columns') && !$request->filled('max_attendance')) {
            $data['max_attendance'] = $request->seating_rows * $request->seating_columns;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($location->image_path) {
                Storage::disk('public')->delete($location->image_path);
            }

            $image = $request->file('image');
            $imageName = 'location_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('locations', $imageName, 'public');
            $data['image_path'] = $imagePath;
        }

        $location->update($data);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        if ($location->auctions()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete location assigned to auctions.']);
        }

        if ($location->items()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete location assigned to items.']);
        }

        $location->delete();

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location deleted successfully.');
    }

    // Delete location image
    public function deleteImage(Location $location)
    {
        if ($location->image_path) {
            Storage::disk('public')->delete($location->image_path);
            $location->update(['image_path' => null]);
        }

        return back()->with('success', 'Image deleted successfully.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $query = Category::query()->with('parent')->withCount(['children', 'events']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $categories = $query->orderBy('name')->paginate(25)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create($request->only(['name', 'parent_id']));

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $categories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();
            
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category->update($request->only(['name', 'parent_id']));

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->children()->count() > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete category with subcategories. Delete subcategories first.'
            ]);
        }

        if ($category->catalogues()->count() > 0 || $category->items()->count() > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete category that is in use. Reassign items/catalogues first.'
            ]);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
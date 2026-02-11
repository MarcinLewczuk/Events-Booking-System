<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show user's tag preferences
    public function index()
    {
        $user = Auth::user();
        $allTags = Tag::orderBy('name')->get();
        $userTagIds = $user->interestedTags()->pluck('tags.id')->toArray();

        return view('profile.tags', compact('allTags', 'userTagIds'));
    }

    // Toggle a tag interest
    public function toggle(Request $request, Tag $tag)
    {
        $user = Auth::user();
        $user->toggleTagInterest($tag->id);

        $isInterested = $user->isInterestedIn($tag->id);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'interested' => $isInterested,
                'message' => $isInterested 
                    ? "Added '{$tag->name}' to your interests" 
                    : "Removed '{$tag->name}' from your interests"
            ]);
        }

        return back()->with('success', $isInterested 
            ? "Added '{$tag->name}' to your interests" 
            : "Removed '{$tag->name}' from your interests");
    }

    // Update multiple tags at once
    public function update(Request $request)
    {
        $request->validate([
            'tags' => 'array',
            'tags.*' => 'exists:tags,id'
        ]);

        $user = Auth::user();
        $user->interestedTags()->sync($request->input('tags', []));

        return back()->with('success', 'Tag preferences updated successfully.');
    }
}
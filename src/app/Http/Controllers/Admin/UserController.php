<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->withCount('interestedTags')->orderBy('created_at', 'desc')->paginate(25)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user (view only)
     */
    public function show(User $user)
    {
        // Load relationships
        $user->load('interestedTags');

        // Get customer statistics if customer
        if ($user->role === 'customer') {
            $stats = [
                'total_bids' => $user->bids()->count(),
                'active_bids' => $user->bids()->whereHas('auctionItem', function($q) {
                    $q->whereHas('auction', function($q) {
                        $q->where('status', 'open');
                    });
                })->count(),
                'won_items' => $user->bids()->where('is_winning', true)
                    ->whereHas('auctionItem', function($q) {
                        $q->whereHas('auction', function($q) {
                            $q->where('status', 'closed');
                        });
                    })->count(),
                'total_spent' => $user->bids()->where('is_winning', true)
                    ->whereHas('auctionItem', function($q) {
                        $q->whereHas('auction', function($q) {
                            $q->where('status', 'closed');
                        });
                    })->sum('amount'),
            ];

            // Get recent bids
            $recentBids = $user->bids()
                ->with(['auctionItem.item.images', 'auctionItem.auction'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        } else {
            $stats = null;
            $recentBids = collect();
        }

        return view('admin.users.show', compact('user', 'stats', 'recentBids'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,staff,approver,customer',
            'contact_telephone_number' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string',
        ]);

        User::create([
            'title' => $request->title,
            'first_name' => $request->first_name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'contact_telephone_number' => $request->contact_telephone_number,
            'contact_address' => $request->contact_address,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'title' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,staff,approver,customer',
            'contact_telephone_number' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string',
            'buyer_approved_status' => 'nullable|boolean',
        ]);

        $data = [
            'title' => $request->title,
            'first_name' => $request->first_name,
            'surname' => $request->surname,
            'email' => $request->email,
            'role' => $request->role,
            'contact_telephone_number' => $request->contact_telephone_number,
            'contact_address' => $request->contact_address,
        ];

        // Only update buyer_approved_status if user is a customer
        if ($user->role === 'customer') {
            $data['buyer_approved_status'] = $request->boolean('buyer_approved_status');
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff,approver']);
    }

    /**
     * Display a listing of customers
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $customers = User::where('role', 'customer')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('surname', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('contact_telephone_number', 'like', "%{$search}%");
                });
            })
            ->when($status === 'approved', function ($query) {
                $query->where('buyer_approved_status', true);
            })
            ->when($status === 'pending', function ($query) {
                $query->where('buyer_approved_status', false);
            })
            ->withCount('interestedTags')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('staff.customers.index', compact('customers', 'search', 'status'));
    }

    /**
     * Display the specified customer
     */
    public function show(User $user)
    {
        // Ensure we're only showing customers
        if ($user->role !== 'customer') {
            abort(404);
        }

        // Load relationships
        $user->load('interestedTags');


        return view('staff.customers.show', compact('user'));
    }
}
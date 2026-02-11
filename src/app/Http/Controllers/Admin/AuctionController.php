<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Catalogue;
use App\Models\Location;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');
    }

    // List auctions
    public function index()
    {
        $auctions = Auction::with(['catalogue', 'location', 'creator', 'approver'])
            ->orderBy('auction_date', 'desc')
            ->paginate(12);

        return view('admin.auctions.index', compact('auctions'));
    }

    // Show create form
    public function create()
    {
        $catalogues = Catalogue::where('status', 'published')
            ->whereDoesntHave('auctions')
            ->orderBy('name')
            ->get();

        $locations = Location::orderBy('name')->get();

        return view('admin.auctions.create', compact('catalogues', 'locations'));
    }


    // Store new auction
    public function store(Request $request)
    {
        $request->validate([
            'catalogue_id' => 'required|exists:catalogues,id',
            'title' => 'required|string|max:255',
            'auction_date' => 'nullable|date',
            'start_time' => 'nullable',
            'location_id' => 'nullable|exists:locations,id',
            'auction_block' => 'nullable|string|max:255',
            'approval_status' => 'nullable|in:draft,awaiting_approval',
        ]);

        $auction = Auction::create([
            'catalogue_id' => $request->catalogue_id,
            'title' => $request->title,
            'auction_date' => $request->auction_date,
            'start_time' => $request->start_time,
            'location_id' => $request->location_id,
            'auction_block' => $request->auction_block,
            'status' => 'scheduled',
            'approval_status' => $request->approval_status ?? 'draft',
            'created_by' => auth()->id(),
        ]);

        // If submitted for approval, set timestamp
        if ($request->approval_status === 'awaiting_approval') {
            $auction->update(['approval_status_changed_at' => now()]);
        }

        return redirect()->route('admin.auctions.index')->with('success', 'Auction created.');
    }

    // Show edit form
    public function edit(Auction $auction)
    {
        $catalogues = Catalogue::where('status', 'published')->orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        return view('admin.auctions.edit', compact('auction', 'catalogues', 'locations'));
    }


    // Update auction
    public function update(Request $request, Auction $auction)
    {
        // Check if auction can be edited
        if (!$auction->canBeEdited()) {
            return back()->withErrors(['error' => 'Cannot edit auction with status: ' . $auction->status]);
        }

        $request->validate([
            'catalogue_id' => 'required|exists:catalogues,id',
            'title' => 'required|string|max:255',
            'auction_date' => 'nullable|date',
            'start_time' => 'nullable',
            'status' => 'required|in:scheduled,open,closed,settled',
            'location_id' => 'nullable|exists:locations,id',
            'auction_block' => 'nullable|string|max:255',
            'approval_status' => 'nullable|in:draft,awaiting_approval,approved,rejected',
        ]);

        $updateData = $request->only([
            'catalogue_id', 'title', 'auction_date', 'start_time', 'status', 'location_id', 'auction_block'
        ]);

        // Handle approval status change
        if ($request->filled('approval_status') && $request->approval_status !== $auction->approval_status) {
            $updateData['approval_status'] = $request->approval_status;
            $updateData['approval_status_changed_at'] = now();
            
            // Clear rejection reason if moving away from rejected
            if ($request->approval_status !== 'rejected') {
                $updateData['rejection_reason'] = null;
            }
        }

        $auction->update($updateData);

        return redirect()->route('admin.auctions.index')->with('success', 'Auction updated.');
    }

    // Delete auction
    public function destroy(Auction $auction)
    {
        // Block deletion for live auctions
        if (in_array($auction->status, ['open', 'closed', 'settled'])) {
            return back()->withErrors(['error' => 'Cannot delete auction with status: ' . $auction->status]);
        }

        $auction->delete();

        return redirect()->route('admin.auctions.index')->with('success', 'Auction deleted.');
    }

    /**
     * Submit auction for approval
     */
    public function submitForApproval(Auction $auction)
    {
        // Check if user owns this auction or is admin
        if ($auction->created_by !== auth()->id() && !auth()->user()->role === 'admin') {
            abort(403, 'Unauthorized action.');
        }

        if ($auction->approval_status !== 'draft' && $auction->approval_status !== 'rejected') {
            return back()->withErrors(['error' => 'Auction cannot be submitted for approval in its current state.']);
        }

        $auction->update([
            'approval_status' => 'awaiting_approval',
            'approval_status_changed_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Auction submitted for approval.');
    }

    /**
     * Approve auction (Admin/Approver only)
     */
    public function approve(Auction $auction)
    {
        // Check approval permission
        if (!in_array(auth()->user()->role, ['admin', 'approver'])) {
            abort(403, 'Unauthorized action.');
        }

        if (!$auction->canBeApproved()) {
            return back()->withErrors(['error' => 'Auction is not awaiting approval.']);
        }

        $auction->update([
            'approval_status' => 'approved',
            'approved_by' => auth()->id(),
            'approval_status_changed_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Auction approved successfully.');
    }

    /**
     * Reject auction (Admin/Approver only)
     */
    public function reject(Request $request, Auction $auction)
    {
        // Check approval permission
        if (!in_array(auth()->user()->role, ['admin', 'approver'])) {
            abort(403, 'Unauthorized action.');
        }

        if (!$auction->canBeApproved()) {
            return back()->withErrors(['error' => 'Auction is not awaiting approval.']);
        }

        $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $auction->update([
            'approval_status' => 'rejected',
            'approved_by' => null,
            'approval_status_changed_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Auction rejected.');
    }
}
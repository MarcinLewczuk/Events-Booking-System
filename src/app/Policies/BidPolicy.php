<?php

namespace App\Policies;

use App\Models\Bid;
use App\Models\User;

class BidPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'approver']);
    }

    public function view(User $user, Bid $bid): bool
    {
        // Staff can view all, customers can view their own bids
        if (in_array($user->role, ['admin', 'staff', 'approver'])) {
            return true;
        }
        return $bid->auctionCustomer->customer_id === $user->id;
    }

    public function create(User $user): bool
    {
        // Customers can place bids, staff can place bids on behalf
        return true;
    }

    public function update(User $user, Bid $bid): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Bid $bid): bool
    {
        return $user->role === 'admin';
    }
}
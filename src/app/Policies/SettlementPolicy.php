<?php

namespace App\Policies;

use App\Models\Settlement;
use App\Models\User;

class SettlementPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function view(User $user, Settlement $settlement): bool
    {
        // Staff can view, customers can view their own settlements
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }
        return $settlement->buyer_customer_id === $user->id || 
               $settlement->seller_customer_id === $user->id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function update(User $user, Settlement $settlement): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function delete(User $user, Settlement $settlement): bool
    {
        return $user->role === 'admin';
    }
}
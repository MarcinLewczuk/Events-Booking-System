<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{
    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin','staff','approver']);
    }

    public function view(User $user, Item $item)
    {
        return true; // everyone can view
    }

    public function create(User $user)
    {
        return in_array($user->role, ['admin','staff']);
    }

    public function update(User $user, Item $item)
    {
        return $user->role === 'admin' || $item->created_by === $user->id;
    }

    public function delete(User $user, Item $item)
    {
        return $user->role === 'admin';
    }

    public function approve(User $user, Item $item)
    {
        return in_array($user->role, ['admin','approver']);
    }



    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Item $item): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Item $item): bool
    {
        return false;
    }
}

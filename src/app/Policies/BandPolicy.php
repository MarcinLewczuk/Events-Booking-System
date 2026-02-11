<?php

namespace App\Policies;

use App\Models\Band;
use App\Models\User;

class BandPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'approver']);
    }

    public function view(User $user, Band $band): bool
    {
        return in_array($user->role, ['admin', 'staff', 'approver']);
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Band $band): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Band $band): bool
    {
        return $user->role === 'admin';
    }
}
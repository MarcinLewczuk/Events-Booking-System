<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'approver']);
    }

    public function view(User $user, User $model): bool
    {
        // Users can view their own profile, staff+ can view anyone
        return $user->id === $model->id || in_array($user->role, ['admin', 'staff', 'approver']);
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, User $model): bool
    {
        // Users can update their own profile, admin can update anyone
        return $user->id === $model->id || $user->role === 'admin';
    }

    public function delete(User $user, User $model): bool
    {
        // Cannot delete yourself, admin can delete others
        return $user->id !== $model->id && $user->role === 'admin';
    }

    public function approve(User $user, User $model): bool
    {
        // Only admin can approve buyers
        return $user->role === 'admin' && $model->role === 'customer';
    }
}
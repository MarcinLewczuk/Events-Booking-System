<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;

class TagPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Tag $tag): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Tag $tag): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Tag $tag): bool
    {
        return $user->role === 'admin';
    }
}
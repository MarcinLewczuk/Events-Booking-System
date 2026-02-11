<?php

namespace App\Policies;

use App\Models\Catalogue;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CataloguePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Catalogue $catalogue): bool
    {
        // Allow staff, approvers, and admin to view any catalogue PDF
        if (in_array($user->role, ['admin', 'staff', 'approver'])) {
            return true;
        }
        
        // Customers can only view published catalogues
        return $catalogue->status === 'published';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Catalogue $catalogue): bool
    {
        // Admin and staff can update/regenerate PDFs
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Catalogue $catalogue): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Catalogue $catalogue): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Catalogue $catalogue): bool
    {
        return false;
    }
}
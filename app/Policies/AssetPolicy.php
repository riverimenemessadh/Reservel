<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    /**
     * Determine if user can view any assets (list page)
     */
    public function viewAny(User $user): bool
    {
        // Everyone authenticated can view asset list
        return true;
    }

    /**
     * Determine if user can view a specific asset
     */
    public function view(User $user, Asset $asset): bool
    {
        // Everyone authenticated can view asset details
        return true;
    }

    /**
     * Determine if user can create assets
     */
    public function create(User $user): bool
    {
        // Only admins can create assets
        return $user->role === 'admin';
    }

    /**
     * Determine if user can update assets
     */
    public function update(User $user, Asset $asset): bool
    {
        // Only admins can update assets
        return $user->role === 'admin';
    }

    /**
     * Determine if user can delete assets
     */
    public function delete(User $user, Asset $asset): bool
    {
        // Only admins can delete assets
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Asset $asset): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Asset $asset): bool
    {
        return false;
    }
}

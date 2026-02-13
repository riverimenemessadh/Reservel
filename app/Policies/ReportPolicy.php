<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Determine if user can view any reports
     */
    public function viewAny(User $user): bool
    {
        // Everyone authenticated can view reports
        return true;
    }

    /**
     * Determine if user can view a specific report
     */
    public function view(User $user, Report $report): bool
    {
        // Everyone authenticated can view reports
        return true;
    }

    /**
     * Determine if user can create reports
     */
    public function create(User $user): bool
    {
        // Everyone authenticated can create reports
        return true;
    }

    /**
     * Determine if user can resolve reports
     */
    public function resolve(User $user, Report $report): bool
    {
        // Only admins can resolve reports
        return $user->role === 'admin';
    }

    /**
     * Determine if user can delete reports
     */
    public function delete(User $user, Report $report): bool
    {
        // Teachers can delete their own pending reports
        // Admins can delete any report
        return ($user->id === $report->user_id && $report->status === 'pending') 
               || $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Report $report): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Report $report): bool
    {
        return false;
    }
}

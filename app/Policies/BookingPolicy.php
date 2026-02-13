<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Determine if user can view any bookings
     */
    public function viewAny(User $user): bool
    {
        // Everyone authenticated can view bookings
        return true;
    }

    /**
     * Determine if user can create bookings
     */
    public function create(User $user): bool
    {
        // Everyone authenticated can create bookings
        return true;
    }

    /**
     * Determine if user can cancel a booking
     */
    public function cancel(User $user, Booking $booking): bool
    {
        // Owner can cancel their own booking
        // Admin can cancel any booking
        return $user->id === $booking->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Booking $booking): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Booking $booking): bool
    {
        return false;
    }
}

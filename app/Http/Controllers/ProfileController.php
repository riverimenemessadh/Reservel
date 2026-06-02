<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the profile for the given user, or the authenticated user if none specified.
     */
    public function show(Request $request, User $user = null): View
    {
        $targetUser = $user && $user->exists ? $user : $request->user();

        if ($request->user()->isTeacher() && $targetUser->id !== $request->user()->id) {
            abort(403);
        }

        $bookings = $targetUser->bookings()
            ->whereDate('start_time', today())
            ->where('status', 'active')
            ->with('asset')
            ->get()
            ->groupBy(function($booking) {
                return $booking->start_time->format('H:i') . ' - ' . $booking->end_time->format('H:i');
            });

        return view('profile.show', [
            'user' => $targetUser,
            'bookings' => $bookings
        ]);
    }
}
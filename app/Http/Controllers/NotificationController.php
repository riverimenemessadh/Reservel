<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        \Log::info('Notifications index called', [
            'user_id' => $user?->id,
            'user_role' => $user?->role,
            'is_admin' => $user?->isAdmin(),
        ]);

        // Only admins can view notifications
        if (!auth()->user()->isAdmin()) {
            \Log::info('User is not admin, returning 403');
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notifications = auth()->user()->notifications()->latest()->get();
        $unreadCount = auth()->user()->unreadNotifications()->count();

        \Log::info('Retrieved notifications from DB', [
            'count' => $notifications->count(),
            'unread_count' => $unreadCount,
            'notifications_raw' => $notifications->toArray(),
        ]);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        // Only admins can mark notifications as read
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete/dismiss a specific notification
     */
    public function destroy(DatabaseNotification $notification, Request $request)
    {
        // Check if user owns this notification
        if ($notification->notifiable_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }
}

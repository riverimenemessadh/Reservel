<?php

namespace Tests\Feature\Reservel;

use App\Models\User;
use App\Notifications\ReportCreated;
use App\Models\Asset;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Notification Tests — SE-13, SE-14, SE-15
 *
 * Covers admin viewing notifications, dismissing a single
 * notification, and dismissing all notifications.
 */
class NotificationTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Helper: seed a real database notification for the admin
    // -------------------------------------------------------------------------

    private function seedNotificationForAdmin(User $admin, User $teacher): void
    {
        $asset  = Asset::factory()->create(['type' => 'equipment', 'status' => 'available']);
        $report = Report::create([
            'user_id'             => $teacher->id,
            'asset_id'            => $asset->id,
            'problem_description' => 'Device overheats',
            'status'              => 'pending',
        ]);

        $admin->notify(new ReportCreated($report));
    }

    // -------------------------------------------------------------------------
    // SE-13: Admin sees notifications in the bell endpoint
    // -------------------------------------------------------------------------

    /**
     * SE-13 (Positive)
     * Admin GETs /notifications after a report notification was sent.
     * Expects a JSON response containing the notification.
     */
    public function test_admin_can_see_notifications(): void
    {
        $admin   = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create();

        $this->seedNotificationForAdmin($admin, $teacher);

        $response = $this->actingAs($admin)->getJson('/notifications');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'notifications',
            'unread_count',
        ]);
        $response->assertJsonPath('unread_count', 1);
    }

    // -------------------------------------------------------------------------
    // SE-14: Admin can dismiss a single notification
    // -------------------------------------------------------------------------

    /**
     * SE-14 (Positive)
     * Admin sends DELETE to /notifications/{id}.
     * Expects the notification is removed from the database.
     */
    public function test_admin_can_dismiss_single_notification(): void
    {
        $admin   = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create();

        $this->seedNotificationForAdmin($admin, $teacher);

        $notification = $admin->notifications()->first();

        $response = $this->actingAs($admin)->deleteJson("/notifications/{$notification->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }

    // -------------------------------------------------------------------------
    // SE-15: Admin can dismiss all notifications
    // -------------------------------------------------------------------------

    /**
     * SE-15 (Positive)
     * Admin sends POST to /notifications/mark-all-read with multiple notifications.
     * Expects all notifications are marked as read.
     */
    public function test_admin_can_dismiss_all_notifications(): void
    {
        $admin   = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create();

        // Seed two notifications
        $this->seedNotificationForAdmin($admin, $teacher);
        $this->seedNotificationForAdmin($admin, $teacher);

        $response = $this->actingAs($admin)->postJson('/notifications/mark-all-read');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertEquals(0, $admin->unreadNotifications()->count());
    }

    // -------------------------------------------------------------------------
    // Extra: Teacher cannot access notifications endpoint
    // -------------------------------------------------------------------------

    /**
     * (Negative)
     * Teacher GETs /notifications.
     * Expects 403 Unauthorized.
     */
    public function test_teacher_cannot_access_notifications(): void
    {
        $teacher = User::factory()->teacher()->create();

        $response = $this->actingAs($teacher)->getJson('/notifications');

        $response->assertStatus(403);
    }
}

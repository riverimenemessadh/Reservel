<?php

namespace Tests\Feature\Reservel;

use App\Models\Asset;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Dashboard Tests — SE-11, SE-12
 *
 * Covers admin dashboard loading with booking data
 * and the assets-in-use endpoint.
 */
class DashboardTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // SE-11: Admin dashboard loads and shows today's bookings
    // -------------------------------------------------------------------------

    /**
     * SE-11 (Positive)
     * Admin visits /dashboard.
     * Expects 200 response and today's booking data is present.
     */
    public function test_admin_dashboard_loads_with_todays_bookings(): void
    {
        $admin   = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create();
        $asset   = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        // Create a booking for today
        Booking::factory()->create([
            'user_id'    => $teacher->id,
            'asset_id'   => $asset->id,
            'start_time' => today()->setTime(9, 0),
            'end_time'   => today()->setTime(11, 0),
            'status'     => 'active',
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee($asset->name);
        $response->assertSee($teacher->name);
    }

    // -------------------------------------------------------------------------
    // SE-12: Admin dashboard loads for admin role
    // -------------------------------------------------------------------------

    /**
     * SE-12 (Positive)
     * Admin visits /dashboard.
     * Expects the admin dashboard view is returned (not teacher dashboard).
     */
    public function test_admin_sees_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        // Admin dashboard contains assets-in-use stat card
        $response->assertViewIs('admin.dashboard');
    }

    // -------------------------------------------------------------------------
    // Extra: Teacher sees teacher dashboard
    // -------------------------------------------------------------------------

    /**
     * (Positive)
     * Teacher visits /dashboard.
     * Expects the teacher dashboard view is returned.
     */
    public function test_teacher_sees_teacher_dashboard(): void
    {
        $teacher = User::factory()->teacher()->create();

        $response = $this->actingAs($teacher)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('teacher.dashboard');
    }
}
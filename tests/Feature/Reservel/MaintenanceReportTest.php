<?php

namespace Tests\Feature\Reservel;

use App\Models\Asset;
use App\Models\Booking;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ReportCreated;
use Tests\TestCase;

/**
 * Maintenance Report Tests — PU-24 to PU-29
 *
 * Verifies the full report lifecycle:
 * filing → asset goes in_repair → future bookings cancelled → admin notified
 * resolving → asset restored
 * cancelling → asset restored
 */
class MaintenanceReportTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // PU-24: Filing a report sets asset status to in_repair
    // -------------------------------------------------------------------------

    public function test_filing_report_sets_asset_to_in_repair(): void
    {
        Notification::fake();

        $teacher = User::factory()->teacher()->create();
        $admin   = User::factory()->admin()->create();
        $asset   = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $this->actingAs($teacher)->post('/reports', [
            'asset_id'            => $asset->id,
            'problem_description' => 'The projector is not working',
        ]);

        $this->assertDatabaseHas('assets', ['id' => $asset->id, 'status' => 'in_repair']);
    }

    // -------------------------------------------------------------------------
    // PU-25: Filing a report cancels future bookings for that asset
    // -------------------------------------------------------------------------

    public function test_filing_report_cancels_future_bookings_for_asset(): void
    {
        Notification::fake();

        $teacherA = User::factory()->teacher()->create();
        $teacherB = User::factory()->teacher()->create();
        $admin    = User::factory()->admin()->create();
        $asset    = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        // Two future bookings from different teachers
        $bookingA = Booking::create([
            'user_id'    => $teacherA->id,
            'asset_id'   => $asset->id,
            'start_time' => now()->addDay()->setHour(9)->setMinute(0)->setSecond(0),
            'end_time'   => now()->addDay()->setHour(11)->setMinute(0)->setSecond(0),
            'status'     => 'active',
        ]);

        $bookingB = Booking::create([
            'user_id'    => $teacherB->id,
            'asset_id'   => $asset->id,
            'start_time' => now()->addDays(2)->setHour(14)->setMinute(0)->setSecond(0),
            'end_time'   => now()->addDays(2)->setHour(16)->setMinute(0)->setSecond(0),
            'status'     => 'active',
        ]);

        $this->actingAs($teacherA)->post('/reports', [
            'asset_id'            => $asset->id,
            'problem_description' => 'Ceiling fan is broken',
        ]);

        $this->assertDatabaseHas('bookings', ['id' => $bookingA->id, 'status' => 'cancelled']);
        $this->assertDatabaseHas('bookings', ['id' => $bookingB->id, 'status' => 'cancelled']);
    }

    // -------------------------------------------------------------------------
    // PU-26: Filing a report sends a notification to the admin
    // -------------------------------------------------------------------------

    public function test_filing_report_sends_notification_to_admin(): void
    {
        Notification::fake();

        $teacher = User::factory()->teacher()->create();
        $admin   = User::factory()->admin()->create();
        $asset   = Asset::factory()->create(['type' => 'equipment', 'status' => 'available']);

        $this->actingAs($teacher)->post('/reports', [
            'asset_id'            => $asset->id,
            'problem_description' => 'Device overheats',
        ]);

        Notification::assertSentTo($admin, ReportCreated::class);
    }

    // -------------------------------------------------------------------------
    // PU-27: Admin resolving a report restores asset to available
    // -------------------------------------------------------------------------

    public function test_admin_resolving_report_restores_asset_to_available(): void
    {
        $admin   = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create();
        $asset   = Asset::factory()->create(['type' => 'room', 'status' => 'in_repair']);

        $report = Report::create([
            'user_id'             => $teacher->id,
            'asset_id'            => $asset->id,
            'problem_description' => 'Window is cracked',
            'status'              => 'pending',
        ]);

        $response = $this->actingAs($admin)->post("/reports/{$report->id}/resolve");
        $response->assertSessionHas('success'); 
        
        $this->assertDatabaseHas('reports', ['id' => $report->id, 'status' => 'resolved']);
        $this->assertDatabaseHas('assets',  ['id' => $asset->id,  'status' => 'available']);
    }

    // -------------------------------------------------------------------------
    // PU-28: Teacher cancelling their report restores asset to available
    // -------------------------------------------------------------------------

    public function test_teacher_cancelling_report_restores_asset_to_available(): void
    {
        $teacher = User::factory()->teacher()->create();
        $asset   = Asset::factory()->create(['type' => 'room', 'status' => 'in_repair']);

        $report = Report::create([
            'user_id'             => $teacher->id,
            'asset_id'            => $asset->id,
            'problem_description' => 'Door handle fell off',
            'status'              => 'pending',
        ]);

        $this->actingAs($teacher)->delete("/reports/{$report->id}");

        $this->assertDatabaseMissing('reports', ['id' => $report->id]);
        $this->assertDatabaseHas('assets', ['id' => $asset->id, 'status' => 'available']);
    }

    // -------------------------------------------------------------------------
    // PU-29: Cannot file a second report on an asset already in repair
    // -------------------------------------------------------------------------

    public function test_cannot_file_report_on_asset_already_in_repair(): void
    {
        Notification::fake();

        $teacherA = User::factory()->teacher()->create();
        $teacherB = User::factory()->teacher()->create();
        $admin    = User::factory()->admin()->create();
        $asset    = Asset::factory()->create(['type' => 'room', 'status' => 'in_repair']);

        // Existing pending report already exists
        Report::create([
            'user_id'             => $teacherA->id,
            'asset_id'            => $asset->id,
            'problem_description' => 'First report',
            'status'              => 'pending',
        ]);

        $response = $this->actingAs($teacherB)->post('/reports', [
            'asset_id'            => $asset->id,
            'problem_description' => 'Second report on same asset',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals(1, Report::where('asset_id', $asset->id)->count());
    }
}

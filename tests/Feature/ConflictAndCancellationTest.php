<?php

namespace Tests\Feature\Reservel;

use App\Models\Asset;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Conflict Detection & Booking Cancellation Tests — PU-14 to PU-23
 *
 * Verifies that the overlap detection logic in BookingService correctly
 * rejects conflicting bookings in all overlap scenarios, and that
 * cancellation rules are enforced properly.
 */
class ConflictAndCancellationTest extends TestCase
{
    use RefreshDatabase;

    /** Create an active booking for a given user and asset at a given time. */
    private function bookAsset(User $user, Asset $asset, string $start, string $end): Booking
    {
        return Booking::create([
            'user_id'    => $user->id,
            'asset_id'   => $asset->id,
            'start_time' => $start,
            'end_time'   => $end,
            'status'     => 'active',
        ]);
    }

    /** Build a POST /bookings payload. */
    private function payload(array $assetIds, string $start, string $end): array
    {
        return [
            'asset_ids'  => $assetIds,
            'start_time' => $start,
            'end_time'   => $end,
        ];
    }

    // -------------------------------------------------------------------------
    // PU-14: Exact same time slot on same asset is rejected
    // -------------------------------------------------------------------------

    public function test_exact_same_timeslot_conflict_is_rejected(): void
    {
        $date     = now()->addDay()->format('Y-m-d');
        $start    = "$date 09:00:00";
        $end      = "$date 11:00:00";

        $teacherA = User::factory()->teacher()->create();
        $teacherB = User::factory()->teacher()->create();
        $room     = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $this->bookAsset($teacherA, $room, $start, $end);

        $response = $this->actingAs($teacherB)->post('/bookings', $this->payload([$room->id], $start, $end));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals(1, Booking::where('asset_id', $room->id)->where('status', 'active')->count());
    }

    // -------------------------------------------------------------------------
    // PU-15: Overlap — new booking starts during existing booking
    // -------------------------------------------------------------------------

    public function test_booking_that_starts_during_existing_booking_is_rejected(): void
    {
        $date     = now()->addDay()->format('Y-m-d');
        $teacherA = User::factory()->teacher()->create();
        $teacherB = User::factory()->teacher()->create();
        $room     = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $this->bookAsset($teacherA, $room, "$date 09:00:00", "$date 11:00:00");

        $response = $this->actingAs($teacherB)->post('/bookings', $this->payload(
            [$room->id], "$date 10:00:00", "$date 12:00:00"
        ));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals(1, Booking::where('asset_id', $room->id)->where('status', 'active')->count());
    }

    // -------------------------------------------------------------------------
    // PU-16: Overlap — new booking ends during existing booking
    // -------------------------------------------------------------------------

    public function test_booking_that_ends_during_existing_booking_is_rejected(): void
    {
        $date     = now()->addDay()->format('Y-m-d');
        $teacherA = User::factory()->teacher()->create();
        $teacherB = User::factory()->teacher()->create();
        $room     = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $this->bookAsset($teacherA, $room, "$date 09:00:00", "$date 11:00:00");

        $response = $this->actingAs($teacherB)->post('/bookings', $this->payload(
            [$room->id], "$date 08:00:00", "$date 10:00:00"
        ));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals(1, Booking::where('asset_id', $room->id)->where('status', 'active')->count());
    }

    // -------------------------------------------------------------------------
    // PU-17: Overlap — new booking fully contains existing booking
    // -------------------------------------------------------------------------

    public function test_booking_that_fully_contains_existing_booking_is_rejected(): void
    {
        $date     = now()->addDay()->format('Y-m-d');
        $teacherA = User::factory()->teacher()->create();
        $teacherB = User::factory()->teacher()->create();
        $room     = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $this->bookAsset($teacherA, $room, "$date 09:00:00", "$date 11:00:00");

        $response = $this->actingAs($teacherB)->post('/bookings', $this->payload(
            [$room->id], "$date 08:00:00", "$date 12:00:00"
        ));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals(1, Booking::where('asset_id', $room->id)->where('status', 'active')->count());
    }

    // -------------------------------------------------------------------------
    // PU-18: Non-overlapping booking on same asset is allowed
    // -------------------------------------------------------------------------

    public function test_non_overlapping_booking_on_same_asset_is_allowed(): void
    {
        $date     = now()->addDay()->format('Y-m-d');
        $teacherA = User::factory()->teacher()->create();
        $teacherB = User::factory()->teacher()->create();
        $room     = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $this->bookAsset($teacherA, $room, "$date 09:00:00", "$date 11:00:00");

        $response = $this->actingAs($teacherB)->post('/bookings', $this->payload(
            [$room->id], "$date 11:00:00", "$date 13:00:00"
        ));

        $response->assertRedirect();
        $this->assertEquals(2, Booking::where('asset_id', $room->id)->where('status', 'active')->count());
    }

    // -------------------------------------------------------------------------
    // PU-19: Same time slot on a different asset has no conflict
    // -------------------------------------------------------------------------

    public function test_same_timeslot_on_different_asset_is_allowed(): void
    {
        $date     = now()->addDay()->format('Y-m-d');
        $start    = "$date 09:00:00";
        $end      = "$date 11:00:00";

        $teacherA = User::factory()->teacher()->create();
        $teacherB = User::factory()->teacher()->create();
        $room1    = Asset::factory()->create(['type' => 'room', 'status' => 'available']);
        $room2    = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $this->bookAsset($teacherA, $room1, $start, $end);

        $response = $this->actingAs($teacherB)->post('/bookings', $this->payload([$room2->id], $start, $end));

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'user_id'  => $teacherB->id,
            'asset_id' => $room2->id,
            'status'   => 'active',
        ]);
    }

    // -------------------------------------------------------------------------
    // PU-20: Asset in repair cannot be booked
    // -------------------------------------------------------------------------

    public function test_asset_in_repair_cannot_be_booked(): void
    {
        $date    = now()->addDay()->format('Y-m-d');
        $teacher = User::factory()->teacher()->create();
        $room    = Asset::factory()->create(['type' => 'room', 'status' => 'in_repair']);

        $response = $this->actingAs($teacher)->post('/bookings', $this->payload(
            [$room->id], "$date 09:00:00", "$date 11:00:00"
        ));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('bookings', ['user_id' => $teacher->id, 'asset_id' => $room->id]);
    }

    // -------------------------------------------------------------------------
    // PU-21: Teacher can cancel their own future booking
    // -------------------------------------------------------------------------

    public function test_teacher_can_cancel_their_own_future_booking(): void
    {
        $teacher = User::factory()->teacher()->create();
        $room    = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $booking = $this->bookAsset(
            $teacher, $room,
            now()->addDay()->setHour(9)->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s'),
            now()->addDay()->setHour(11)->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s')
        );

        $response = $this->actingAs($teacher)->delete("/bookings/{$booking->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'cancelled']);
    }

    // -------------------------------------------------------------------------
    // PU-22: Teacher cannot cancel another teacher's booking
    // -------------------------------------------------------------------------

    public function test_teacher_cannot_cancel_another_teachers_booking(): void
    {
        $teacherA = User::factory()->teacher()->create();
        $teacherB = User::factory()->teacher()->create();
        $room     = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $booking = $this->bookAsset(
            $teacherA, $room,
            now()->addDay()->setHour(9)->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s'),
            now()->addDay()->setHour(11)->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s')
        );

        $response = $this->actingAs($teacherB)->delete("/bookings/{$booking->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'active']);
    }

    // -------------------------------------------------------------------------
    // PU-23: Past booking cannot be cancelled
    // -------------------------------------------------------------------------

    public function test_past_booking_cannot_be_cancelled(): void
    {
        $teacher = User::factory()->teacher()->create();
        $room    = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $booking = $this->bookAsset(
            $teacher, $room,
            now()->subHours(4)->format('Y-m-d H:i:s'),
            now()->subHours(2)->format('Y-m-d H:i:s')
        );

        $response = $this->actingAs($teacher)->delete("/bookings/{$booking->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'active']);
    }
}
<?php

namespace Tests\Feature\Reservel;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Booking Rules Tests — PU-07 to PU-13
 *
 * Verifies that the booking system enforces all business rules:
 * allowed hours, time order, room limit, and equipment limit.
 */
class BookingRulesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Build a valid booking payload for the given asset IDs.
     * Uses tomorrow at 09:00–11:00 by default (within allowed hours).
     */
    private function payload(array $assetIds, string $start = null, string $end = null): array
    {
        return [
            'asset_ids'  => $assetIds,
            'start_time' => $start ?? now()->addDay()->format('Y-m-d') . ' 09:00:00',
            'end_time'   => $end   ?? now()->addDay()->format('Y-m-d') . ' 11:00:00',
        ];
    }

    // -------------------------------------------------------------------------
    // PU-07: Valid booking is created successfully
    // -------------------------------------------------------------------------

    public function test_valid_booking_is_created_successfully(): void
    {
        $teacher = User::factory()->teacher()->create();
        $room    = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $response = $this->actingAs($teacher)->post('/bookings', $this->payload([$room->id]));

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'user_id'  => $teacher->id,
            'asset_id' => $room->id,
            'status'   => 'active',
        ]);
    }

    // -------------------------------------------------------------------------
    // PU-08: Booking starting before 07:00 is rejected
    // -------------------------------------------------------------------------

    public function test_booking_before_0700_is_rejected(): void
    {
        $teacher = User::factory()->teacher()->create();
        $room    = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $date     = now()->addDay()->format('Y-m-d');
        $response = $this->actingAs($teacher)->post('/bookings', $this->payload(
            [$room->id],
            "$date 06:00:00",
            "$date 08:00:00"
        ));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('bookings', ['user_id' => $teacher->id, 'asset_id' => $room->id]);
    }

    // -------------------------------------------------------------------------
    // PU-09: Booking ending after 20:00 is rejected
    // -------------------------------------------------------------------------

    public function test_booking_ending_after_2000_is_rejected(): void
    {
        $teacher = User::factory()->teacher()->create();
        $room    = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $date     = now()->addDay()->format('Y-m-d');
        $response = $this->actingAs($teacher)->post('/bookings', $this->payload(
            [$room->id],
            "$date 19:00:00",
            "$date 21:00:00"
        ));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('bookings', ['user_id' => $teacher->id, 'asset_id' => $room->id]);
    }

    // -------------------------------------------------------------------------
    // PU-10: Booking with end time before start time is rejected
    // -------------------------------------------------------------------------

    public function test_booking_with_end_before_start_is_rejected(): void
    {
        $teacher = User::factory()->teacher()->create();
        $room    = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $date     = now()->addDay()->format('Y-m-d');
        $response = $this->actingAs($teacher)->post('/bookings', [
            'asset_ids'  => [$room->id],
            'start_time' => "$date 11:00:00",
            'end_time'   => "$date 09:00:00",
        ]);

        // Laravel's own validation catches after:start_time before BookingService
        $response->assertSessionHasErrors('end_time');
        $this->assertDatabaseMissing('bookings', ['user_id' => $teacher->id, 'asset_id' => $room->id]);
    }

    // -------------------------------------------------------------------------
    // PU-11: Booking more than one room per session is rejected
    // -------------------------------------------------------------------------

    public function test_booking_more_than_one_room_is_rejected(): void
    {
        $teacher = User::factory()->teacher()->create();
        $room1   = Asset::factory()->create(['type' => 'room', 'status' => 'available']);
        $room2   = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $response = $this->actingAs($teacher)->post('/bookings', $this->payload([$room1->id, $room2->id]));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('bookings', ['user_id' => $teacher->id]);
    }

    // -------------------------------------------------------------------------
    // PU-12: Booking more than 5 equipment per session is rejected
    // -------------------------------------------------------------------------

    public function test_booking_more_than_five_equipment_is_rejected(): void
    {
        $teacher   = User::factory()->teacher()->create();
        $equipment = Asset::factory()->count(6)->create(['type' => 'equipment', 'status' => 'available']);

        $response = $this->actingAs($teacher)->post('/bookings', $this->payload(
            $equipment->pluck('id')->toArray()
        ));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('bookings', ['user_id' => $teacher->id]);
    }

    // -------------------------------------------------------------------------
    // PU-13: Booking exactly 5 equipment per session is allowed
    // -------------------------------------------------------------------------

    public function test_booking_exactly_five_equipment_is_allowed(): void
    {
        $teacher   = User::factory()->teacher()->create();
        $equipment = Asset::factory()->count(5)->create(['type' => 'equipment', 'status' => 'available']);

        $response = $this->actingAs($teacher)->post('/bookings', $this->payload(
            $equipment->pluck('id')->toArray()
        ));

        $response->assertRedirect();
        $this->assertEquals(
            5,
            \App\Models\Booking::where('user_id', $teacher->id)->where('status', 'active')->count()
        );
    }
}
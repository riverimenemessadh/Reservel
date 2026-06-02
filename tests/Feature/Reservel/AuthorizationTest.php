<?php

namespace Tests\Feature\Reservel;

use App\Models\Asset;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Authorization Tests — PU-01 to PU-06
 *
 * Verifies that role-based access control is enforced correctly.
 * Teachers must not be able to perform admin-only actions.
 */
class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // PU-01: Teacher cannot create an asset
    // -------------------------------------------------------------------------

    public function test_teacher_cannot_create_asset(): void
    {
        $teacher = User::factory()->teacher()->create();

        $response = $this->actingAs($teacher)->post('/assets', [
            'name'        => 'Unauthorized Room',
            'type'        => 'room',
            'description' => 'Should not be created',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('assets', ['name' => 'Unauthorized Room']);
    }

    // -------------------------------------------------------------------------
    // PU-02: Teacher cannot edit an asset
    // -------------------------------------------------------------------------

    public function test_teacher_cannot_edit_asset(): void
    {
        $teacher = User::factory()->teacher()->create();
        $asset   = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $response = $this->actingAs($teacher)->put("/assets/{$asset->id}", [
            'name'        => 'Hacked Name',
            'type'        => 'room',
            'description' => 'Unauthorized edit',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('assets', ['name' => 'Hacked Name']);
    }

    // -------------------------------------------------------------------------
    // PU-03: Teacher cannot delete an asset
    // -------------------------------------------------------------------------

    public function test_teacher_cannot_delete_asset(): void
    {
        $teacher = User::factory()->teacher()->create();
        $asset   = Asset::factory()->create(['type' => 'room', 'status' => 'available']);

        $response = $this->actingAs($teacher)->delete("/assets/{$asset->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('assets', ['id' => $asset->id]);
    }

    // -------------------------------------------------------------------------
    // PU-04: Admin can create an asset
    // -------------------------------------------------------------------------

    public function test_admin_can_create_asset(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/assets', [
            'name'        => 'New Conference Room',
            'type'        => 'room',
            'description' => 'A brand new room',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('assets', ['name' => 'New Conference Room', 'type' => 'room']);
    }

    // -------------------------------------------------------------------------
    // PU-05: Unauthenticated user is redirected to login
    // -------------------------------------------------------------------------

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    // -------------------------------------------------------------------------
    // PU-06: Teacher cannot resolve a maintenance report
    // -------------------------------------------------------------------------

    public function test_teacher_cannot_resolve_report(): void
    {
        $teacher = User::factory()->teacher()->create();
        $asset   = Asset::factory()->create(['type' => 'room', 'status' => 'in_repair']);

        $report = Report::create([
            'user_id'             => $teacher->id,
            'asset_id'            => $asset->id,
            'problem_description' => 'Projector is broken',
            'status'              => 'pending',
        ]);

        $response = $this->actingAs($teacher)->post("/reports/{$report->id}/resolve");

        $response->assertStatus(403);
        $this->assertDatabaseHas('reports', ['id' => $report->id, 'status' => 'pending']);
    }
}
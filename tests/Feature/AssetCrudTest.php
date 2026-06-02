<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Asset CRUD Tests — PU-30 to PU-33
 *
 * Covers admin creating, updating, and deleting assets,
 * and validation of required fields.
 */
class AssetCrudTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function teacher(): User
    {
        return User::factory()->create(['role' => 'teacher']);
    }

    private function validAssetPayload(array $overrides = []): array
    {
        return array_merge([
            'name'        => 'Salle Alpha',
            'type'        => 'room',
            'description' => 'A test room.',
            'image'       => null,
        ], $overrides);
    }

    // -------------------------------------------------------------------------
    // PU-30 — Admin can create a room asset
    // -------------------------------------------------------------------------

    /**
     * PU-30 (Positive)
     * Admin POSTs to /assets with valid data.
     * Expects a redirect and the asset to exist in the database.
     */
    public function test_admin_can_create_a_room_asset(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->post('/assets', $this->validAssetPayload());

        $response->assertRedirect();

        $this->assertDatabaseHas('assets', [
            'name' => 'Salle Alpha',
            'type' => 'room',
        ]);
    }

    // -------------------------------------------------------------------------
    // PU-31 — Admin can update an asset name
    // -------------------------------------------------------------------------

    /**
     * PU-31 (Positive)
     * Admin PUTs to /assets/{id} with an updated name.
     * Expects a redirect and the new name in the database.
     */
    public function test_admin_can_update_an_asset_name(): void
    {
        $admin = $this->admin();
        $asset = Asset::factory()->create([
            'name' => 'Old Name',
            'type' => 'room',
        ]);

        $response = $this->actingAs($admin)
            ->put("/assets/{$asset->id}", $this->validAssetPayload(['name' => 'New Name']));

        $response->assertRedirect();

        $this->assertDatabaseHas('assets', [
            'id'   => $asset->id,
            'name' => 'New Name',
        ]);
    }

    // -------------------------------------------------------------------------
    // PU-32 — Admin can delete an asset
    // -------------------------------------------------------------------------

    /**
     * PU-32 (Positive)
     * Admin sends DELETE to /assets/{id}.
     * Expects a redirect and the asset to be gone from the database.
     *
     * Note: the asset has no active bookings so no constraint blocks deletion.
     */
    public function test_admin_can_delete_an_asset(): void
    {
        $admin = $this->admin();
        $asset = Asset::factory()->create(['type' => 'equipment']);

        $response = $this->actingAs($admin)
            ->delete("/assets/{$asset->id}");

        $response->assertRedirect();

        $this->assertDatabaseMissing('assets', ['id' => $asset->id]);
    }

    // -------------------------------------------------------------------------
    // PU-33 — Asset name is required — empty name is rejected
    // -------------------------------------------------------------------------

    /**
     * PU-33 (Negative)
     * Admin POSTs to /assets with an empty name.
     * Expects a validation error and no new asset in the database.
     */
    public function test_asset_name_is_required(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->post('/assets', $this->validAssetPayload(['name' => '']));

        $response->assertSessionHasErrors('name');

        $this->assertDatabaseCount('assets', 0);
    }
}
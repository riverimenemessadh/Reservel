<?php

namespace Tests\Feature\Reservel;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Authentication Tests — SE-01, SE-02, SE-03
 *
 * Covers login with valid credentials, login with wrong password,
 * and logout.
 */
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // SE-01: Teacher can log in with valid credentials
    // -------------------------------------------------------------------------

    /**
     * SE-01 (Positive)
     * Teacher submits correct email and password.
     * Expects redirect to /dashboard and user is authenticated.
     */
    public function test_teacher_can_login_with_valid_credentials(): void
    {
        $teacher = User::factory()->teacher()->create();

        $response = $this->post('/login', [
            'email'    => $teacher->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($teacher);
    }

    // -------------------------------------------------------------------------
    // SE-02: Login fails with wrong password
    // -------------------------------------------------------------------------

    /**
     * SE-02 (Negative)
     * Teacher submits correct email but wrong password.
     * Expects user stays on login page with an error.
     */
    public function test_login_fails_with_wrong_password(): void
    {
        $teacher = User::factory()->teacher()->create();

        $response = $this->post('/login', [
            'email'    => $teacher->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // -------------------------------------------------------------------------
    // SE-03: User can log out
    // -------------------------------------------------------------------------

    /**
     * SE-03 (Positive)
     * Authenticated teacher sends a POST to /logout.
     * Expects redirect to /login and user is no longer authenticated.
     */
    public function test_user_can_logout(): void
    {
        $teacher = User::factory()->teacher()->create();

        $this->actingAs($teacher)->post('/logout');

        $this->assertGuest();
    }
}
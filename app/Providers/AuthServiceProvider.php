<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\Report;
use App\Models\Booking;
use App\Policies\AssetPolicy;
use App\Policies\ReportPolicy;
use App\Policies\BookingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

/**
 * AuthServiceProvider
 *
 * Registers model-to-policy mappings and defines global Gate rules
 * for the Reservel resource booking system.
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Asset::class => AssetPolicy::class,
        Report::class => ReportPolicy::class,
        Booking::class => BookingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * Calls registerPolicies() to wire up the model-policy mappings above,
     * then defines global Gate definitions for role-based access control.
     *
     * @return void
     */
    public function boot(): void
    {
        // Register policies automatically
        $this->registerPolicies();

        // Currently unused — controllers check $user->isAdmin() directly.
        // Available for future use via Gate::allows('access-admin-dashboard').
        Gate::define('access-admin-dashboard', function ($user) {
            return $user->role === 'admin';
        });

        // Currently unused — controllers check $user->isAdmin() directly.
        // Available for future use via Gate::allows('access-teacher-dashboard').
        Gate::define('access-teacher-dashboard', function ($user) {
            return $user->role === 'teacher' || $user->role === 'admin';
        });

        // Currently unused — controllers check $user->isAdmin() directly.
        // Available for future use via Gate::allows('manage-users').
        Gate::define('manage-users', function ($user) {
            return $user->role === 'admin';
        });
    }
}
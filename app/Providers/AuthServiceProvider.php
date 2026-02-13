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

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     */
    protected $policies = [
        Asset::class => AssetPolicy::class,
        Report::class => ReportPolicy::class,
        Booking::class => BookingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Register policies automatically
        $this->registerPolicies();

        // Define global gates for dashboard access
        Gate::define('access-admin-dashboard', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('access-teacher-dashboard', function ($user) {
            return $user->role === 'teacher' || $user->role === 'admin';
        });

        Gate::define('manage-users', function ($user) {
            return $user->role === 'admin';
        });
    }
}

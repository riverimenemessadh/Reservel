<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('/', function () {
    return view('auth.login');
});

// Language Switching
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['fr', 'ar'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('locale.set');

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Assets
    Route::resource('assets', AssetController::class);

    // Bookings
    Route::resource('bookings', BookingController::class)->except(['edit', 'update']);

    // Reports
    Route::resource('reports', ReportController::class)->only(['index', 'store']);
    Route::post('/reports/{report}/resolve', [ReportController::class, 'resolve'])->name('reports.resolve');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

    // Profile
    Route::get('/profile/{user?}', [ProfileController::class, 'show'])->name('profile.show');
});

require __DIR__ . '/auth.php';
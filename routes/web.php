<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Language switching for guests and authenticated users
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['fr', 'ar'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('locale.set');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::resource('assets', AssetController::class);
    Route::resource('bookings', BookingController::class)->except(['edit', 'update']);
    Route::resource('reports', ReportController::class)->only(['index', 'store']);
    Route::post('/reports/{report}/resolve', [ReportController::class, 'resolve'])->name('reports.resolve');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
    Route::get('/profile/{user?}', [ProfileController::class, 'show'])->name('profile.show');
});

require __DIR__ . '/auth.php';

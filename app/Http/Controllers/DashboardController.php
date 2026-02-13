<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Booking;
use App\Models\Report;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
        $this->bookingService->updateAssetStatuses();
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->teacherDashboard();
    }

    protected function adminDashboard()
    {
        $todayBookings = $this->getGroupedBookings(today(), null);
        $recentBookings = $this->getGroupedBookings(now()->subDays(30), null, 10);

        $inUseAssets = Asset::where('status', 'in_use')->with('currentBooking.user')->get();
        $lastReport = Report::where('status', 'pending')
            ->with(['user', 'asset'])
            ->latest()
            ->first();

        $stats = [
            'total_assets' => Asset::count(),
            'in_use_now' => $inUseAssets->count(),
            'in_use_assets' => $inUseAssets,
            'available_assets' => Asset::where('status', 'available')->count(),
            'total_bookings' => DB::table('bookings')
                ->where('status', 'active')
                ->select('user_id', 'start_time', 'end_time')
                ->distinct()
                ->count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'last_report' => $lastReport,
            'maintenance_due' => Asset::where('status', 'in_repair')->count(),
        ];

        $teachers = User::where('role', 'teacher')->get();

        return view('admin.dashboard', compact('todayBookings', 'recentBookings', 'stats', 'teachers'));
    }

    protected function teacherDashboard()
    {
        $user = auth()->user();

        $this->bookingService->updateAssetStatuses();

        $todayBookings = $this->getGroupedBookings(today(), $user->id);
        $recentBookings = $this->getGroupedBookings(now()->subDays(30), $user->id, 10);

        $rooms = Asset::where('type', 'room')->get();
        $equipment = Asset::where('type', 'equipment')->get();

        return view('teacher.dashboard', compact('todayBookings', 'recentBookings', 'rooms', 'equipment'));
    }

    protected function getGroupedBookings($fromDate, $userId = null, $limit = null)
    {
        $query = Booking::select([
                'user_id',
                'start_time',
                'end_time',
                DB::raw('MIN(id) as id'),
                DB::raw('COUNT(*) as asset_count')
            ])
            ->where('status', 'active')
            ->where('start_time', '>=', $fromDate)
            ->groupBy('user_id', 'start_time', 'end_time')
            ->orderBy('start_time', 'desc');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($limit) {
            $query->limit($limit);
        }

        $groupedBookings = $query->get();

        return $groupedBookings->map(function($group) {
            $bookings = Booking::where('user_id', $group->user_id)
                ->where('start_time', $group->start_time)
                ->where('end_time', $group->end_time)
                ->where('status', 'active')
                ->with('asset')
                ->get();

            $currentUser = auth()->user();
            $isBookingOwner = $currentUser->id === $group->user_id;
            $isAdmin = $currentUser->isAdmin();
            $bookingHasNotEnded = $group->end_time > now();

            return (object)[
                'id' => $group->id,
                'user_id' => $group->user_id,
                'user' => User::find($group->user_id),
                'start_time' => $group->start_time,
                'end_time' => $group->end_time,
                'asset_count' => $group->asset_count,
                'bookings' => $bookings,
                'can_cancel' => $bookingHasNotEnded && ($isAdmin || $isBookingOwner)
            ];
        });
    }

    public function setLocale($locale)
    {
        if (in_array($locale, ['fr', 'ar'])) {
            session(['locale' => $locale]);
        }
        return redirect()->back();
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Booking;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index()
    {
        $this->bookingService->updateAssetStatuses();
        
        $user = auth()->user();
        
        $query = Booking::select([
                'user_id',
                'start_time',
                'end_time',
                DB::raw('MIN(id) as id'),
                DB::raw('COUNT(*) as asset_count')
            ])
            ->where('status', 'active')
            ->groupBy('user_id', 'start_time', 'end_time')
            ->orderBy('start_time', 'desc');

        if ($user->isTeacher()) {
            $query->where('user_id', $user->id);
        }

        $groupedBookings = $query->paginate(20);

        $bookings = $groupedBookings->map(function($group) {
            $bookings = Booking::where('user_id', $group->user_id)
                ->where('start_time', $group->start_time)
                ->where('end_time', $group->end_time)
                ->where('status', 'active')
                ->with('asset')
                ->get();

            return (object)[
                'id' => $group->id,
                'user_id' => $group->user_id,
                'user' => User::find($group->user_id),
                'start_time' => $group->start_time,
                'end_time' => $group->end_time,
                'asset_count' => $group->asset_count,
                'bookings' => $bookings,
                'can_cancel' => now()->lt($group->end_time)
            ];
        });

        return view('bookings.index', compact('bookings', 'groupedBookings'));
    }

    public function create()
    {
        $rooms = Asset::where('type', 'room')
            ->where('status', '!=', 'in_repair')
            ->get();
        
        $equipment = Asset::where('type', 'equipment')
            ->where('status', '!=', 'in_repair')
            ->get();

        return view('bookings.create', compact('rooms', 'equipment'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'asset_ids' => 'required|array',
            'asset_ids.*' => 'exists:assets,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $result = $this->bookingService->createBooking(
            $user->id,
            $request->asset_ids,
            $request->start_time,
            $request->end_time
        );

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message'])->withInput();
        }

        return redirect()->route('dashboard')->with('success', $result['message']);
    }

    public function destroy($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        
        $this->authorize('cancel', $booking);

        if (now()->gte($booking->end_time)) {
            return redirect()->back()->with('error', __('messages.error_booking_ended'));
        }

        $result = $this->bookingService->cancelBooking($bookingId);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->back()->with('success', $result['message']);
    }
}
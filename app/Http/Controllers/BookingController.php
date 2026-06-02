<?php

namespace App\Http\Controllers;

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

    /**
     * Display a paginated list of active bookings grouped by user, start time, and end time.
     * Teachers only see their own bookings; admins see all.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
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

        $userIds = $groupedBookings->pluck('user_id')->unique()->all();
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        $groupKeys = $groupedBookings->map(fn($g) => [
            'user_id'    => $g->user_id,
            'start_time' => $g->start_time,
            'end_time'   => $g->end_time,
        ])->all();

        $allBookings = Booking::with('asset')
            ->where('status', 'active')
            ->where(function ($query) use ($groupKeys) {
                foreach ($groupKeys as $key) {
                    $query->orWhere(function ($q) use ($key) {
                        $q->where('user_id', $key['user_id'])
                          ->where('start_time', $key['start_time'])
                          ->where('end_time', $key['end_time']);
                    });
                }
            })
            ->get()
            ->groupBy(fn($b) => $b->user_id . '|' . $b->start_time . '|' . $b->end_time);

        $bookings = $groupedBookings->map(function ($group) use ($users, $allBookings) {
            $key = $group->user_id . '|' . $group->start_time . '|' . $group->end_time;

            return (object) [
                'id'          => $group->id,
                'user_id'     => $group->user_id,
                'user'        => $users->get($group->user_id),
                'start_time'  => $group->start_time,
                'end_time'    => $group->end_time,
                'asset_count' => $group->asset_count,
                'bookings'    => $allBookings->get($key, collect()),
                'can_cancel'  => now()->lt($group->end_time),
            ];
        });

        return view('bookings.index', compact('bookings', 'groupedBookings'));
    }

    /**
     * Handle the form submission to create one or more bookings for the given assets and time range.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'asset_ids'   => 'required|array',
            'asset_ids.*' => 'exists:assets,id',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
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

    /**
     * Cancel an existing booking by ID, provided the booking has not yet ended
     * and the authenticated user is authorised to cancel it.
     *
     * @param  int  $bookingId
     * @return \Illuminate\Http\RedirectResponse
     */
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
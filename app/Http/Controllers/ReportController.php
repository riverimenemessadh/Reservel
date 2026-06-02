<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Report;
use App\Models\User;
use App\Models\Booking;
use App\Notifications\ReportCreated;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected $bookingService;

    /**
     * Create a new controller instance.
     */
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display a listing of reports.
     */
    public function index()
    {
        $user = auth()->user();

        $query = Report::with(['user', 'asset'])
            ->orderByRaw("FIELD(status, 'pending', 'resolved')")
            ->orderBy('created_at', 'desc');

        if ($user->isTeacher()) {
            $query->where('user_id', $user->id);
        }

        $reports = $query->paginate(20);

        return view('reports.index', compact('reports'));
    }

    /**
     * Store a newly created report in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $this->authorize('create', Report::class);

        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'problem_description' => 'required|string',
            'possible_cause' => 'nullable|string',
        ]);

        $asset = Asset::find($request->asset_id);
        if ($asset->status === 'in_repair') {
            return redirect()->back()->with('error', __('messages.asset_already_in_repair'));
        }

        DB::beginTransaction();
        try {
            $report = Report::create([
                'user_id' => $user->id,
                'asset_id' => $request->asset_id,
                'problem_description' => $request->problem_description,
                'possible_cause' => $request->possible_cause,
            ]);

            $asset->update(['status' => 'in_repair']);

            Booking::where('asset_id', $asset->id)
                ->where('status', 'active')
                ->where('end_time', '>', now())
                ->update(['status' => 'cancelled']);

            DB::commit();

            $admins = User::admins()->get();
            foreach ($admins as $admin) {
                $admin->notify(new ReportCreated($report));
            }

            return redirect()->route('assets.index')->with('success', __('messages.report_submitted'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('messages.error_submitting_report'));
        }
    }

    /**
     * Mark a report as resolved and restore the asset status if no pending reports remain.
     */
    public function resolve(Report $report)
    {
        $this->authorize('resolve', $report);

        DB::beginTransaction();
        try {
            $report->update(['status' => 'resolved']);

            $hasPendingReports = Report::where('asset_id', $report->asset_id)
                ->where('status', 'pending')
                ->exists();

            if (!$hasPendingReports) {
                $this->restoreAssetStatus($report->asset()->firstOrFail());
            }

            DB::commit();
            return redirect()->back()->with('success', __('messages.report_resolved'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('messages.error_resolving_report'));
        }
    }

    /**
     * Delete a pending report and restore the asset status if no pending reports remain.
     */
    public function destroy(Report $report)
    {
        $this->authorize('delete', $report);

        if ($report->status !== 'pending') {
            return redirect()->back()->with('error', __('messages.error_cancel_resolved_report'));
        }

        DB::beginTransaction();
        try {
            $asset = $report->asset;
            $report->delete();

            $hasPendingReports = Report::where('asset_id', $asset->id)
                ->where('status', 'pending')
                ->exists();

            if (!$hasPendingReports) {
                $this->restoreAssetStatus($asset);
            }

            DB::commit();
            return redirect()->back()->with('success', __('messages.report_cancelled'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('messages.error_cancelling_report'));
        }
    }

    /**
     * Restore an asset's status to 'in_use' or 'available' based on active bookings.
     */
    private function restoreAssetStatus(Asset $asset): void
    {
        $hasActiveBooking = Booking::where('asset_id', $asset->id)
            ->where('status', 'active')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->exists();

        $asset->update([
            'status' => $hasActiveBooking ? 'in_use' : 'available'
        ]);
    }
}

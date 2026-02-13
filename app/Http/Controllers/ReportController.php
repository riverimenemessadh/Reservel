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

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index()
    {
        $this->bookingService->updateAssetStatuses();
        
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

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'problem_description' => 'required|string',
            'possible_cause' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $report = Report::create([
                'user_id' => $user->id,
                'asset_id' => $request->asset_id,
                'problem_description' => $request->problem_description,
                'possible_cause' => $request->possible_cause,
            ]);

            $asset = Asset::find($request->asset_id);
            $asset->update(['status' => 'in_repair']);

            Booking::where('asset_id', $asset->id)
                ->where('status', 'active')
                ->where('end_time', '>', now())
                ->update(['status' => 'cancelled']);

            $admins = User::where('role', 'admin')->get();
            \Log::info('Found admins for notification', ['admin_count' => $admins->count()]);
            
            foreach ($admins as $admin) {
                \Log::info('Sending notification to admin', ['admin_id' => $admin->id, 'admin_name' => $admin->name]);
                $admin->notify(new ReportCreated($report));
            }

            DB::commit();
            return redirect()->route('assets.index')->with('success', __('messages.report_submitted'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('messages.error_submitting_report'));
        }
    }

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
                $hasActiveBooking = Booking::where('asset_id', $report->asset_id)
                    ->where('status', 'active')
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>=', now())
                    ->exists();

                $report->asset->update([
                    'status' => $hasActiveBooking ? 'in_use' : 'available'
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', __('messages.report_resolved'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('messages.error_resolving_report'));
        }
    }
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
                $hasActiveBooking = Booking::where('asset_id', $asset->id)
                    ->where('status', 'active')
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>=', now())
                    ->exists();

                $asset->update([
                    'status' => $hasActiveBooking ? 'in_use' : 'available'
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', __('messages.report_cancelled'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('messages.error_cancelling_report'));
        }
    }
}

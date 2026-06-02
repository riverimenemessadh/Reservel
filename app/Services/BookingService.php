<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Validate that a booking falls within allowed operating hours (07:00–20:00)
     * and that the start time precedes the end time.
     *
     * @param  string  $startTime
     * @param  string  $endTime
     * @return array{valid: bool, message?: string}
     */
    public function validateBookingTime($startTime, $endTime)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        $openingTime = $start->copy()->setTimeFromTimeString('07:00:00');
        $closingTime = $start->copy()->setTimeFromTimeString('20:00:00');

        if ($start->lt($openingTime) || $end->gt($closingTime)) {
            return [
                'valid' => false,
                'message' => __('messages.error_booking_hours')
            ];
        }

        if ($start->gte($end)) {
            return [
                'valid' => false,
                'message' => __('messages.error_start_end_time')
            ];
        }

        return ['valid' => true];
    }

    /**
     * Check whether an asset is free for the requested time slot,
     * optionally excluding a specific booking (e.g. when editing).
     *
     * @param  int       $assetId
     * @param  string    $startTime
     * @param  string    $endTime
     * @param  int|null  $excludeBookingId
     * @return bool  True when the asset is available, false when a conflict exists.
     */
    public function checkAssetAvailability($assetId, $startTime, $endTime, $excludeBookingId = null)
    {
        $query = Booking::where('asset_id', $assetId)
            ->where('status', 'active')
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return !$query->exists();
    }

    /**
     * Run all business-rule validations against a booking request before
     * it is persisted: room/equipment limits, operating hours, asset repair
     * status, and availability conflicts.
     *
     * @param  \Illuminate\Support\Collection|array  $assets
     * @param  string  $startTime
     * @param  string  $endTime
     * @return array{valid: bool, message?: string}
     */
    public function validateBookingRequest($assets, $startTime, $endTime)
    {
        $rooms = collect($assets)->where('type', 'room');
        $equipment = collect($assets)->where('type', 'equipment');

        if ($rooms->count() > 1) {
            return [
                'valid' => false,
                'message' => __('messages.error_single_room_only')
            ];
        }

        if ($equipment->count() > 5) {
            return [
                'valid' => false,
                'message' => __('messages.error_max_equipment_reached')
            ];
        }

        $timeValidation = $this->validateBookingTime($startTime, $endTime);
        if (!$timeValidation['valid']) {
            return $timeValidation;
        }

        foreach ($assets as $asset) {
            if ($asset->status === 'in_repair') {
                return [
                    'valid' => false,
                    'message' => __('messages.error_asset_in_repair')
                ];
            }

            if (!$this->checkAssetAvailability($asset->id, $startTime, $endTime)) {
                return [
                    'valid' => false,
                    'message' => __('messages.error_asset_unavailable')
                ];
            }
        }

        return ['valid' => true];
    }

    /**
     * Validate and persist a new booking for one or more assets.
     * All asset bookings are created inside a single database transaction.
     *
     * @param  int    $userId
     * @param  array  $assetIds
     * @param  string $startTime
     * @param  string $endTime
     * @return array{success: bool, message: string, bookings?: \App\Models\Booking[]}
     */
    public function createBooking($userId, $assetIds, $startTime, $endTime)
    {
        $assets = Asset::whereIn('id', $assetIds)->get();

        $validation = $this->validateBookingRequest($assets, $startTime, $endTime);

        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $validation['message']
            ];
        }

        DB::beginTransaction();
        try {
            $bookings = [];

            foreach ($assets as $asset) {
                $booking = Booking::create([
                    'user_id' => $userId,
                    'asset_id' => $asset->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'status' => 'active',
                ]);
                $bookings[] = $booking;
            }

            DB::commit();
            return [
                'success' => true,
                'message' => __('messages.booking_success'),
                'bookings' => $bookings
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => __('messages.error_creating_booking')
            ];
        }
    }

    /**
     * Cancel an existing booking and all sibling bookings that share the same
     * user, start time, and end time. Assets are marked available when no
     * other active booking is currently occupying them.
     *
     * @param  int  $bookingId
     * @return array{success: bool, message: string}
     */
    public function cancelBooking($bookingId)
    {
        DB::beginTransaction();
        try {
            $booking = Booking::findOrFail($bookingId);

            $relatedBookings = Booking::active()
                ->forTimeSlot($booking->user_id, $booking->start_time, $booking->end_time)
                ->get();

            foreach ($relatedBookings as $relatedBooking) {
                $relatedBooking->update(['status' => 'cancelled']);

                $hasOtherActiveBookings = Booking::active()
                    ->where('asset_id', $relatedBooking->asset_id)
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>=', now())
                    ->exists();

                if (!$hasOtherActiveBookings) {
                    $asset = Asset::find($relatedBooking->asset_id);
                    if ($asset && $asset->status !== 'in_repair') {
                        $asset->update(['status' => 'available']);
                    }
                }
            }

            DB::commit();
            return [
                'success' => true,
                'message' => __('messages.booking_cancelled')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => __('messages.error_cancelling_booking')
            ];
        }
    }

    /**
     * Synchronise every asset's status with the current set of active bookings.
     * Assets whose bookings have ended are flipped back to "available";
     * assets with a running booking are set to "in_use".
     * Assets flagged as "in_repair" are never touched.
     *
     * @return void
     */
    public function updateAssetStatuses()
    {
        $now = now();

        $activeBookings = Booking::where('status', 'active')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->with('asset')
            ->get();

        $activeAssetIds = $activeBookings->pluck('asset_id')->unique();

        Asset::where('status', 'in_use')
            ->whereNotIn('id', $activeAssetIds)
            ->whereDoesntHave('reports', function ($query) {
                $query->where('status', 'pending');
            })
            ->update(['status' => 'available']);

        foreach ($activeBookings as $booking) {
            if ($booking->asset->status !== 'in_repair') {
                $booking->asset->update(['status' => 'in_use']);
            }
        }
    }
}

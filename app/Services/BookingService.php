<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function validateBookingTime($startTime, $endTime)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        if ($start->hour < 7 || $end->hour > 20 || ($end->hour == 20 && $end->minute > 0)) {
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

    public function checkAssetAvailability($assetId, $startTime, $endTime, $excludeBookingId = null)
    {
        $query = Booking::where('asset_id', $assetId)
            ->where('status', 'active')
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q2) use ($startTime, $endTime) {
                        $q2->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return !$query->exists();
    }

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

            $this->updateAssetStatuses();

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

    public function cancelBooking($bookingId)
    {
        DB::beginTransaction();
        try {
            $booking = Booking::findOrFail($bookingId);

            $relatedBookings = Booking::where('user_id', $booking->user_id)
                ->where('start_time', $booking->start_time)
                ->where('end_time', $booking->end_time)
                ->where('status', 'active')
                ->get();

            foreach ($relatedBookings as $relatedBooking) {
                $relatedBooking->update(['status' => 'cancelled']);

                $hasOtherActiveBookings = Booking::where('asset_id', $relatedBooking->asset_id)
                    ->where('status', 'active')
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

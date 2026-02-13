<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\BookingService;
use Symfony\Component\HttpFoundation\Response;

class UpdateAssetStatusesMiddleware
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        // Only update statuses for authenticated users on protected routes
        if ($request->user()) {
            try {
                $this->bookingService->updateAssetStatuses();
            } catch (\Exception $e) {
                // Log the error but don't break the request
                \Log::error('UpdateAssetStatuses failed: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}

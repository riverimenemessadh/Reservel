<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\BookingService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to update asset statuses on each authenticated request.
 *
 * Ensures that asset availability and booking statuses are kept up to date
 * before any protected route is handled. Failures are logged silently without
 * interrupting the request lifecycle.
 */
class UpdateAssetStatusesMiddleware
{
    /**
     * The booking service instance.
     *
     * @var BookingService
     */
    protected $bookingService;

    /**
     * Create a new middleware instance.
     *
     * @param BookingService $bookingService
     */
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Handle an incoming request.
     *
     * Triggers asset status synchronisation for authenticated users before
     * passing the request to the next middleware or controller. Any exception
     * thrown during the update is caught and logged so that the request is
     * never blocked by a status-update failure.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response
     */
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
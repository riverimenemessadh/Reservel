<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to set the application locale for each incoming request.
 *
 * If a locale has been stored in the session, it will be applied;
 * otherwise, the application defaults to French ('fr').
 */
class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Checks the session for a stored locale value and sets the application
     * locale accordingly. Falls back to 'fr' if no locale is found in the session.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request.
     * @param  \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response  $next  The next middleware handler.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('locale')) {
            App::setLocale(session('locale'));
        } else {
            App::setLocale('fr');
        }

        return $next($request);
    }
}
<?php

namespace Componist\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('auth.2fa_enabled') && Auth::check() && Auth::user()->two_factor_code && Auth::user()->two_factor_expires_at->isFuture()) {
            return redirect()->route('2fa.index');
        }

        return $next($request);
    }
}
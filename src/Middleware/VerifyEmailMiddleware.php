<?php

namespace Componist\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmailMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // nicht angemeldet
        if (Auth::check() === false) {
            return redirect()->route('login');
        }
        // mail ist noch nicht bestätigt
        if (Auth::user()->email_verified_at === null) {
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
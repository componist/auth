<?php

declare(strict_types=1);

namespace Componist\Auth\Middleware;

use Closure;
use Componist\Auth\Support\ComponistAuthConfig;
use Componist\Auth\Support\TwoFactorSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    /**
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! ComponistAuthConfig::twoFactorEnabled()) {
            return $next($request);
        }

        if (! TwoFactorSession::isConfirmed(Auth::user())) {
            return redirect()->route('componist.auth.twoFactorAuth');
        }

        return $next($request);
    }
}

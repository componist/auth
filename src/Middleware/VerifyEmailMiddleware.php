<?php

declare(strict_types=1);

namespace Componist\Auth\Middleware;

use Closure;
use Componist\Auth\Support\ComponistAuthConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmailMiddleware
{
    /**
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! ComponistAuthConfig::verificationEnabled()) {
            return $next($request);
        }

        if (! Auth::check()) {
            return redirect()->route(ComponistAuthConfig::loginRoute());
        }

        $user = Auth::user();

        if ($user !== null && $user->email_verified_at === null) {
            return redirect()->route(ComponistAuthConfig::verificationNoticeRoute());
        }

        return $next($request);
    }
}

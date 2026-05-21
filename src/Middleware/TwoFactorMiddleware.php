<?php

declare(strict_types=1);

namespace Componist\Auth\Middleware;

use Closure;
use Componist\Auth\Support\ComponistAuthConfig;
use Illuminate\Database\Eloquent\Model;
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

        $user = Auth::user();

        if (
            $user instanceof Model
            && is_string($user->getAttribute('two_factor_code'))
            && $user->getAttribute('two_factor_code') !== ''
        ) {
            return redirect()->route('componist.auth.twoFactorAuth');
        }

        return $next($request);
    }
}

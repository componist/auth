<?php

declare(strict_types=1);

namespace Componist\Auth\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    /**
     * @param  array<int, string>  $guards
     */
    public function handle($request, Closure $next, ...$guards): Response
    {
        $this->authenticate($request, $guards);

        return app(EnsureSecondaryAuthentication::class)->handle($request, $next);
    }
}

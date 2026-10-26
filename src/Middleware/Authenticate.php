<?php

declare(strict_types=1);

namespace Componist\Auth\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    /**
     * @param  string  ...$guards
     */
    public function handle($request, Closure $next, ...$guards): Response
    {
        $this->authenticate($request, $guards);

        return app(EnsureSecondaryAuthentication::class)->handle($request, $next);
    }
}

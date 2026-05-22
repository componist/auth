<?php

declare(strict_types=1);

namespace Componist\Auth\Support;

use Illuminate\Support\Facades\Route;

final class ComponistAuthRouteAliases
{
    /**
     * Laravel-Standard-Routennamen → primär registrierte Package-Routen.
     *
     * @var array<string, string>
     */
    private const STANDARD_TO_PACKAGE = [
        'login' => 'componist.auth.login',
        'password.request' => 'componist.auth.password.request',
        'password.reset' => 'componist.auth.password.reset',
        'verification.notice' => 'componist.auth.verification.notice',
        'verification.verify' => 'componist.auth.verification.verify',
    ];

    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function resolve(string $name, array $parameters, bool $absolute): ?string
    {
        $target = self::STANDARD_TO_PACKAGE[$name] ?? null;

        if ($target === null || ! Route::has($target)) {
            return null;
        }

        return route($target, $parameters, $absolute);
    }
}

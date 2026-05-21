<?php

declare(strict_types=1);

namespace Componist\Auth\Support;

use Illuminate\Support\Facades\Route;

final class ComponistAuthRouteAliases
{
    /**
     * Legacy-Routennamen → primär registrierte Laravel-Standard-Routen.
     *
     * @var array<string, string>
     */
    private const LEGACY_TO_PRIMARY = [
        'componist.auth.login' => 'login',
        'componist.auth.password.request' => 'password.request',
        'componist.auth.password.reset' => 'password.reset',
        'componist.auth.verification.notice' => 'verification.notice',
        'componist.auth.verification.verify' => 'verification.verify',
    ];

    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function resolve(string $name, array $parameters, bool $absolute): ?string
    {
        $target = self::LEGACY_TO_PRIMARY[$name] ?? null;

        if ($target === null || ! Route::has($target)) {
            return null;
        }

        return route($target, $parameters, $absolute);
    }
}

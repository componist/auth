<?php

declare(strict_types=1);

namespace Componist\Auth\Application;

use Componist\Auth\Domain\TwoFactorCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

final class TwoFactorAuthService
{
    public static function verify(Model $user, string $code): bool
    {
        return TwoFactorCode::verify($code, $user->getAttribute('two_factor_code'));
    }

    public static function expiresAt(Model $user): ?Carbon
    {
        $expiresAt = $user->getAttribute('two_factor_expires_at');

        return $expiresAt instanceof Carbon ? $expiresAt : null;
    }

    public static function hash(string $code): string
    {
        return TwoFactorCode::hash($code);
    }
}

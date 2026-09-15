<?php

declare(strict_types=1);

namespace Componist\Auth\Support;

use Illuminate\Contracts\Auth\Authenticatable;

final class TwoFactorSession
{
    public const KEY = 'componist_auth.two_factor_confirmed_user_id';

    public static function confirm(int|string $userId): void
    {
        session()->put(self::KEY, (int) $userId);
    }

    public static function forget(): void
    {
        session()->forget(self::KEY);
    }

    public static function isConfirmed(?Authenticatable $user): bool
    {
        if ($user === null) {
            return false;
        }

        return (int) session(self::KEY) === (int) $user->getAuthIdentifier();
    }
}

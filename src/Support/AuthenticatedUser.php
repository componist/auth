<?php

declare(strict_types=1);

namespace Componist\Auth\Support;

use Componist\Auth\Contracts\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

final class AuthenticatedUser
{
    /**
     * @return Model&TwoFactorAuthenticatable
     */
    public static function twoFactor(): Model
    {
        $user = Auth::user();

        if (! $user instanceof Model) {
            throw new RuntimeException('Authenticated user must be an Eloquent model implementing TwoFactorAuthenticatable.');
        }

        return $user;
    }
}

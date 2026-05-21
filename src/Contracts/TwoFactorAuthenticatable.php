<?php

declare(strict_types=1);

namespace Componist\Auth\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Carbon;

/**
 * @property string $name
 * @property string|null $two_factor_code
 * @property Carbon|null $two_factor_expires_at
 */
interface TwoFactorAuthenticatable extends Authenticatable, MustVerifyEmail
{
    public function generateTwoFactorCode(): void;

    public function resetTwoFactorCode(): void;
}

<?php

declare(strict_types=1);

namespace Componist\Auth\PhpStan;

use Componist\Auth\Contracts\TwoFactorAuthenticatable;
use Componist\Auth\Traits\AddComponistAuthentication;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;

/**
 * @property string $name
 * @property string|null $two_factor_code
 * @property Carbon|null $two_factor_expires_at
 *
 * @internal Used only for PHPStan trait analysis.
 */
class StubUser extends Authenticatable implements TwoFactorAuthenticatable
{
    use AddComponistAuthentication;
}

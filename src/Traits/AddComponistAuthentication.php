<?php

declare(strict_types=1);

namespace Componist\Auth\Traits;

use Componist\Auth\Contracts\TwoFactorAuthenticatable;
use Componist\Auth\Domain\TwoFactorCode as TwoFactorCodeHasher;
use Componist\Auth\Notifications\TwoFactorCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

trait AddComponistAuthentication
{
    public function generateTwoFactorCode(): void
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->forceFill([
            'two_factor_code' => TwoFactorCodeHasher::hash($code),
            'two_factor_expires_at' => now()->addMinutes(10),
        ])->save();

        if (! $this instanceof TwoFactorAuthenticatable) {
            return;
        }

        Notification::send($this, new TwoFactorCode($this, $code));
    }

    public function resetTwoFactorCode(): void
    {
        $this->forceFill([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ])->save();
    }

    public static function hashTwoFactorCode(string $code): string
    {
        return TwoFactorCodeHasher::hash($code);
    }

    public static function verifyTwoFactorCode(Model $user, string $code): bool
    {
        return TwoFactorCodeHasher::verify($code, $user->getAttribute('two_factor_code'));
    }

    public static function twoFactorExpiresAt(Model $user): ?Carbon
    {
        $expiresAt = $user->getAttribute('two_factor_expires_at');

        return $expiresAt instanceof Carbon ? $expiresAt : null;
    }

    protected function initializeAddComponistAuthentication(): void
    {
        $this->casts['two_factor_expires_at'] = 'datetime';
    }
}

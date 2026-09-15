<?php

declare(strict_types=1);

namespace Componist\Auth\Domain;

final class TwoFactorCode
{
    public static function hash(string $code): string
    {
        return hash('sha256', $code);
    }

    public static function verify(string $plainCode, mixed $storedHash): bool
    {
        if (! is_string($storedHash) || $storedHash === '') {
            return false;
        }

        return hash_equals($storedHash, self::hash($plainCode));
    }

    public static function isValidFormat(string $code): bool
    {
        return (bool) preg_match('/^\d{6}$/', $code);
    }
}

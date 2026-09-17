<?php

declare(strict_types=1);

namespace Componist\Auth\Domain;

use InvalidArgumentException;

final class TwoFactorCode
{
    public const CHARSET_DIGITS = 'digits';

    public const CHARSET_ALPHANUMERIC = 'alphanumeric';

    private const DIGITS_ALPHABET = '0123456789';

    private const ALPHANUMERIC_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

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

    public static function generate(string $charset = self::CHARSET_ALPHANUMERIC, int $length = 12): string
    {
        $length = self::normalizeLength($length);
        $alphabet = self::alphabet($charset);
        $max = strlen($alphabet) - 1;
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $alphabet[random_int(0, $max)];
        }

        return $code;
    }

    public static function alphabet(string $charset): string
    {
        return match (self::normalizeCharset($charset)) {
            self::CHARSET_DIGITS => self::DIGITS_ALPHABET,
            self::CHARSET_ALPHANUMERIC => self::ALPHANUMERIC_ALPHABET,
        };
    }

    public static function normalize(string $code, string $charset = self::CHARSET_ALPHANUMERIC): string
    {
        $trimmed = trim($code);

        return match (self::normalizeCharset($charset)) {
            self::CHARSET_DIGITS => $trimmed,
            self::CHARSET_ALPHANUMERIC => strtoupper($trimmed),
        };
    }

    public static function isValidFormat(
        string $code,
        string $charset = self::CHARSET_ALPHANUMERIC,
        int $length = 12,
    ): bool {
        $length = self::normalizeLength($length);
        $normalized = self::normalize($code, $charset);

        if (strlen($normalized) !== $length) {
            return false;
        }

        $alphabet = self::alphabet($charset);

        return (bool) preg_match('/^['.preg_quote($alphabet, '/').']+$/', $normalized);
    }

    /**
     * @return list<string>
     */
    public static function validationRules(string $charset = self::CHARSET_ALPHANUMERIC, int $length = 12): array
    {
        $length = self::normalizeLength($length);
        $charset = self::normalizeCharset($charset);

        return match ($charset) {
            self::CHARSET_DIGITS => ['required', 'string', 'digits:'.$length],
            self::CHARSET_ALPHANUMERIC => [
                'required',
                'string',
                'size:'.$length,
                'regex:/^['.preg_quote(self::ALPHANUMERIC_ALPHABET, '/').']+$/i',
            ],
        };
    }

    public static function normalizeCharset(string $charset): string
    {
        $charset = strtolower(trim($charset));

        if (! in_array($charset, [self::CHARSET_DIGITS, self::CHARSET_ALPHANUMERIC], true)) {
            throw new InvalidArgumentException(
                "Ungültiger 2FA-Zeichensatz [{$charset}]. Erlaubt: digits, alphanumeric."
            );
        }

        return $charset;
    }

    public static function normalizeLength(int $length): int
    {
        if ($length < 4 || $length > 12) {
            throw new InvalidArgumentException('2FA-Code-Länge muss zwischen 4 und 12 liegen.');
        }

        return $length;
    }
}

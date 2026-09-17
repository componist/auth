<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Unit\Domain;

use Componist\Auth\Domain\TwoFactorCode;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TwoFactorCodeTest extends TestCase
{
    public function test_hash_is_sha256_of_plain_code(): void
    {
        $this->assertSame(
            hash('sha256', '123456'),
            TwoFactorCode::hash('123456')
        );
    }

    public function test_verify_accepts_valid_code(): void
    {
        $hash = TwoFactorCode::hash('654321');

        $this->assertTrue(TwoFactorCode::verify('654321', $hash));
    }

    public function test_verify_rejects_invalid_code(): void
    {
        $hash = TwoFactorCode::hash('654321');

        $this->assertFalse(TwoFactorCode::verify('000000', $hash));
    }

    public function test_verify_rejects_empty_stored_hash(): void
    {
        $this->assertFalse(TwoFactorCode::verify('123456', null));
        $this->assertFalse(TwoFactorCode::verify('123456', ''));
    }

    public function test_is_valid_format_digits_requires_six_digits(): void
    {
        $this->assertTrue(TwoFactorCode::isValidFormat('123456', TwoFactorCode::CHARSET_DIGITS, 6));
        $this->assertFalse(TwoFactorCode::isValidFormat('12345', TwoFactorCode::CHARSET_DIGITS, 6));
        $this->assertFalse(TwoFactorCode::isValidFormat('abcdef', TwoFactorCode::CHARSET_DIGITS, 6));
    }

    public function test_is_valid_format_alphanumeric_allows_letters_and_digits(): void
    {
        $this->assertTrue(TwoFactorCode::isValidFormat('AB12CD', TwoFactorCode::CHARSET_ALPHANUMERIC, 6));
        $this->assertTrue(TwoFactorCode::isValidFormat('ab12cd', TwoFactorCode::CHARSET_ALPHANUMERIC, 6));
        $this->assertFalse(TwoFactorCode::isValidFormat('AB12', TwoFactorCode::CHARSET_ALPHANUMERIC, 6));
        $this->assertFalse(TwoFactorCode::isValidFormat('AB12@!', TwoFactorCode::CHARSET_ALPHANUMERIC, 6));
    }

    public function test_generate_digits_returns_only_digits_of_configured_length(): void
    {
        $code = TwoFactorCode::generate(TwoFactorCode::CHARSET_DIGITS, 6);

        $this->assertMatchesRegularExpression('/^\d{6}$/', $code);
    }

    public function test_generate_alphanumeric_returns_uppercase_letters_and_digits(): void
    {
        $code = TwoFactorCode::generate(TwoFactorCode::CHARSET_ALPHANUMERIC, 8);

        $this->assertMatchesRegularExpression('/^[A-Z0-9]{8}$/', $code);
    }

    public function test_normalize_uppercases_alphanumeric_codes(): void
    {
        $this->assertSame('AB12CD', TwoFactorCode::normalize('ab12cd', TwoFactorCode::CHARSET_ALPHANUMERIC));
        $this->assertSame('123456', TwoFactorCode::normalize('123456', TwoFactorCode::CHARSET_DIGITS));
    }

    public function test_invalid_charset_throws(): void
    {
        $this->expectException(InvalidArgumentException::class);

        TwoFactorCode::normalizeCharset('emoji');
    }
}

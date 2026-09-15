<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Unit\Domain;

use Componist\Auth\Domain\TwoFactorCode;
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

    public function test_is_valid_format_requires_six_digits(): void
    {
        $this->assertTrue(TwoFactorCode::isValidFormat('123456'));
        $this->assertFalse(TwoFactorCode::isValidFormat('12345'));
        $this->assertFalse(TwoFactorCode::isValidFormat('abcdef'));
    }
}

<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Unit;

use App\Models\User;
use Componist\Auth\Notifications\TwoFactorCode;
use Componist\Auth\Tests\TestCase;
use Componist\Auth\Traits\AddComponistAuthentication;
use Illuminate\Support\Facades\Notification;

class AddComponistAuthenticationTest extends TestCase
{
    public function test_hash_two_factor_code_is_sha256_of_plain_code(): void
    {
        $this->assertSame(
            hash('sha256', '123456'),
            AddComponistAuthentication::hashTwoFactorCode('123456')
        );
    }

    public function test_verify_two_factor_code_accepts_valid_code(): void
    {
        $user = $this->userWithTwoFactorCode($this->createUser(), '654321');

        $this->assertTrue(AddComponistAuthentication::verifyTwoFactorCode($user, '654321'));
    }

    public function test_verify_two_factor_code_rejects_invalid_code(): void
    {
        $user = $this->userWithTwoFactorCode($this->createUser(), '654321');

        $this->assertFalse(AddComponistAuthentication::verifyTwoFactorCode($user, '000000'));
    }

    public function test_verify_two_factor_code_rejects_empty_stored_code(): void
    {
        $user = $this->createUser();

        $this->assertFalse(AddComponistAuthentication::verifyTwoFactorCode($user, '123456'));
    }

    public function test_generate_two_factor_code_stores_hash_and_sends_notification(): void
    {
        Notification::fake();

        $user = $this->createUser();

        $user->generateTwoFactorCode();

        $user->refresh();

        $this->assertNotNull($user->two_factor_code);
        $this->assertNotSame('123456', $user->two_factor_code);
        $this->assertTrue($user->two_factor_expires_at?->isFuture() ?? false);

        Notification::assertSentTo($user, TwoFactorCode::class);
    }

    public function test_reset_two_factor_code_clears_fields(): void
    {
        $user = $this->userWithTwoFactorCode($this->createUser());

        $user->resetTwoFactorCode();
        $user->refresh();

        $this->assertNull($user->two_factor_code);
        $this->assertNull($user->two_factor_expires_at);
    }

    public function test_two_factor_expires_at_returns_carbon_instance(): void
    {
        $expiresAt = now()->addMinutes(5);
        $user = $this->userWithTwoFactorCode($this->createUser(), '111111', $expiresAt);

        $resolved = AddComponistAuthentication::twoFactorExpiresAt($user);

        $this->assertNotNull($resolved);
        $this->assertSame($expiresAt->toDateTimeString(), $resolved->toDateTimeString());
    }
}

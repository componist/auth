<?php

declare(strict_types=1);

namespace Componist\Auth\Tests;

use App\Models\User;
use Componist\Auth\Traits\AddComponistAuthentication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        View::addNamespace('componist-auth-tests', __DIR__.'/views');

        config([
            'componist_auth.layouts-app' => 'componist-auth-tests::layouts.guest',
            'componist_auth.home' => 'dashboard.index',
            'componist_auth.verification' => false,
            'componist_auth.two-factor' => false,
            'componist_auth.features.register' => true,
            'componist_auth.features.resetPasswords' => true,
            'componist_auth.user_model' => User::class,
            'componist_auth.login.example.email' => null,
            'componist_auth.login.example.password' => null,
        ]);

    }

    protected function clearRateLimiter(string $key): void
    {
        RateLimiter::clear($key);
    }

    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    protected function createUserWithPassword(string $password = 'password'): User
    {
        return User::factory()->create([
            'password' => $password,
        ]);
    }

    protected function userWithTwoFactorCode(User $user, string $plainCode = '123456', ?\Illuminate\Support\Carbon $expiresAt = null): User
    {
        $user->forceFill([
            'two_factor_code' => AddComponistAuthentication::hashTwoFactorCode($plainCode),
            'two_factor_expires_at' => $expiresAt ?? now()->addMinutes(10),
        ])->save();

        return $user->fresh();
    }

    protected function enableTwoFactor(): void
    {
        config(['componist_auth.two-factor' => true]);
    }

    protected function enableVerification(): void
    {
        config(['componist_auth.verification' => true]);
    }
}

<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Unit;

use App\Models\User;
use Componist\Auth\Support\ComponistAuthConfig;
use Componist\Auth\Tests\TestCase;
use InvalidArgumentException;

class ComponistAuthConfigTest extends TestCase
{
    public function test_home_route_returns_configured_route_name(): void
    {
        config(['componist_auth.home' => 'dashboard.index']);

        $this->assertSame('dashboard.index', ComponistAuthConfig::homeRoute());
    }

    public function test_user_model_returns_configured_class(): void
    {
        $this->assertSame(User::class, ComponistAuthConfig::userModel());
    }

    public function test_user_model_throws_for_invalid_class(): void
    {
        config(['componist_auth.user_model' => \stdClass::class]);

        $this->expectException(InvalidArgumentException::class);

        ComponistAuthConfig::userModel();
    }

    public function test_feature_flags_reflect_config(): void
    {
        config([
            'componist_auth.verification' => true,
            'componist_auth.two-factor' => true,
            'componist_auth.features.register' => false,
        ]);

        $this->assertTrue(ComponistAuthConfig::verificationEnabled());
        $this->assertTrue(ComponistAuthConfig::twoFactorEnabled());
        $this->assertFalse(ComponistAuthConfig::registerEnabled());
    }
}

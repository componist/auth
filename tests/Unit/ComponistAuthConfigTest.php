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

    public function test_login_route_returns_configured_route_name(): void
    {
        config(['componist_auth.routes.login' => 'login']);

        $this->assertSame('login', ComponistAuthConfig::loginRoute());
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

    public function test_two_factor_code_defaults_to_alphanumeric_length_twelve(): void
    {
        config([
            'componist_auth.two_factor_code.charset' => 'alphanumeric',
            'componist_auth.two_factor_code.length' => 12,
        ]);

        $this->assertSame('alphanumeric', ComponistAuthConfig::twoFactorCodeCharset());
        $this->assertSame(12, ComponistAuthConfig::twoFactorCodeLength());
    }

    public function test_two_factor_code_charset_can_be_digits(): void
    {
        config(['componist_auth.two_factor_code.charset' => 'digits']);

        $this->assertSame('digits', ComponistAuthConfig::twoFactorCodeCharset());
    }
}

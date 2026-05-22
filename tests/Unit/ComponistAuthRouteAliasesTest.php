<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Unit;

use Componist\Auth\Support\ComponistAuthRouteAliases;
use Componist\Auth\Tests\TestCase;

class ComponistAuthRouteAliasesTest extends TestCase
{
    public function test_standard_login_alias_resolves_to_package_route(): void
    {
        $this->assertSame(
            route('componist.auth.login'),
            ComponistAuthRouteAliases::resolve('login', [], true),
        );
    }

    public function test_standard_verification_notice_alias_resolves_to_package_route(): void
    {
        $this->assertSame(
            route('componist.auth.verification.notice'),
            ComponistAuthRouteAliases::resolve('verification.notice', [], true),
        );
    }

    public function test_unknown_route_name_returns_null(): void
    {
        $this->assertNull(ComponistAuthRouteAliases::resolve('unknown.route', [], true));
    }

    public function test_package_route_name_is_not_resolved_by_alias(): void
    {
        $this->assertNull(ComponistAuthRouteAliases::resolve('componist.auth.login', [], true));
    }
}

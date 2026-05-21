<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Unit;

use Componist\Auth\Support\ComponistAuthRouteAliases;
use Componist\Auth\Tests\TestCase;

class ComponistAuthRouteAliasesTest extends TestCase
{
    public function test_legacy_login_alias_resolves_to_login_route(): void
    {
        $this->assertSame(route('login'), ComponistAuthRouteAliases::resolve('componist.auth.login', [], true));
    }

    public function test_unknown_route_name_returns_null(): void
    {
        $this->assertNull(ComponistAuthRouteAliases::resolve('unknown.route', [], true));
    }
}

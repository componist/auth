<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Feature\Middleware;

use Componist\Auth\Middleware\TwoFactorMiddleware;
use Componist\Auth\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TwoFactorMiddlewareTest extends TestCase
{
    public function test_passes_through_when_two_factor_is_disabled(): void
    {
        config(['componist_auth.two-factor' => false]);

        $user = $this->createUser();
        $this->actingAs($user);

        $middleware = new TwoFactorMiddleware;
        $request = Request::create('/dashboard', 'GET');

        $response = $middleware->handle($request, fn () => new Response('OK'));

        $this->assertSame('OK', $response->getContent());
    }

    public function test_redirects_to_two_factor_page_when_code_is_pending(): void
    {
        $this->enableTwoFactor();

        $user = $this->createUser();
        $this->userWithTwoFactorCode($user);
        $this->actingAs($user);

        $middleware = new TwoFactorMiddleware;
        $request = Request::create('/dashboard', 'GET');

        $response = $middleware->handle($request, fn () => new Response('OK'));

        $this->assertTrue($response->isRedirect(route('componist.auth.twoFactorAuth')));
    }

    public function test_redirects_to_two_factor_page_when_code_has_expired(): void
    {
        $this->enableTwoFactor();

        $user = $this->createUser();
        $this->userWithTwoFactorCode($user, '123456', now()->subMinute());
        $this->actingAs($user);

        $middleware = new TwoFactorMiddleware;
        $request = Request::create('/dashboard', 'GET');

        $response = $middleware->handle($request, fn () => new Response('OK'));

        $this->assertTrue($response->isRedirect(route('componist.auth.twoFactorAuth')));
    }
}

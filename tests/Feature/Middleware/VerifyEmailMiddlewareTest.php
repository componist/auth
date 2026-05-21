<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Feature\Middleware;

use Componist\Auth\Middleware\VerifyEmailMiddleware;
use Componist\Auth\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyEmailMiddlewareTest extends TestCase
{
    public function test_passes_through_when_verification_is_disabled(): void
    {
        config(['componist_auth.verification' => false]);

        $user = $this->createUser(['email_verified_at' => null]);
        $this->actingAs($user);

        $middleware = new VerifyEmailMiddleware;
        $request = Request::create('/dashboard', 'GET');

        $response = $middleware->handle($request, fn () => new Response('OK'));

        $this->assertSame('OK', $response->getContent());
    }

    public function test_redirects_when_verification_is_enabled_and_email_is_unverified(): void
    {
        $this->enableVerification();

        $user = $this->createUser(['email_verified_at' => null]);
        $this->actingAs($user);

        $middleware = new VerifyEmailMiddleware;
        $request = Request::create('/dashboard', 'GET');

        $response = $middleware->handle($request, fn () => new Response('OK'));

        $this->assertTrue($response->isRedirect(route('verification.notice')));
    }
}

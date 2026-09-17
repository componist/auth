<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Feature\Middleware;

use Componist\Auth\Middleware\EnsureSecondaryAuthentication;
use Componist\Auth\Support\TwoFactorSession;
use Componist\Auth\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Route;

class EnsureSecondaryAuthenticationTest extends TestCase
{
    public function test_mixed_livewire_batch_with_forged_companion_does_not_skip_two_factor(): void
    {
        $this->enableTwoFactor();

        $user = $this->createUser();
        $this->actingAs($user);
        TwoFactorSession::forget();

        $request = $this->livewireUpdateRequest([
            $this->snapshot('dashboard.some-component'),
            $this->snapshot('auth.login'),
        ]);

        $response = (new EnsureSecondaryAuthentication)->handle(
            $request,
            fn () => new Response('BYPASS'),
        );

        $this->assertTrue($response->isRedirect(route('componist.auth.twoFactorAuth')));
        $this->assertNotSame('BYPASS', $response->getContent());
    }

    public function test_mixed_livewire_batch_with_challenge_companion_does_not_skip_two_factor(): void
    {
        $this->enableTwoFactor();

        $user = $this->createUser();
        $this->actingAs($user);
        TwoFactorSession::forget();

        $request = $this->livewireUpdateRequest([
            $this->snapshot('dashboard.some-component'),
            $this->snapshot('auth.two-factor-auth-controller'),
        ]);

        $response = (new EnsureSecondaryAuthentication)->handle(
            $request,
            fn () => new Response('BYPASS'),
        );

        $this->assertTrue($response->isRedirect(route('componist.auth.twoFactorAuth')));
    }

    public function test_challenge_only_livewire_batch_skips_two_factor_gate(): void
    {
        $this->enableTwoFactor();

        $user = $this->createUser();
        $this->actingAs($user);
        TwoFactorSession::forget();

        $request = $this->livewireUpdateRequest([
            $this->snapshot('auth.two-factor-auth-controller'),
        ]);

        $response = (new EnsureSecondaryAuthentication)->handle(
            $request,
            fn () => new Response('OK'),
        );

        $this->assertSame('OK', $response->getContent());
    }

    public function test_empty_livewire_components_do_not_skip_two_factor(): void
    {
        $this->enableTwoFactor();

        $user = $this->createUser();
        $this->actingAs($user);
        TwoFactorSession::forget();

        $request = $this->livewireUpdateRequest([]);

        $response = (new EnsureSecondaryAuthentication)->handle(
            $request,
            fn () => new Response('OK'),
        );

        $this->assertTrue($response->isRedirect(route('componist.auth.twoFactorAuth')));
    }

    /**
     * @param  list<string>  $snapshots
     */
    private function livewireUpdateRequest(array $snapshots): Request
    {
        $components = array_map(
            static fn (string $snapshot): array => [
                'snapshot' => $snapshot,
                'updates' => [],
                'calls' => [],
            ],
            $snapshots,
        );

        $request = Request::create('/livewire/update', 'POST', [
            'components' => $components,
        ]);

        $route = new Route(['POST'], '/livewire/update', static fn () => null);
        $route->name('default-livewire.update');
        $route->bind($request);
        $request->setRouteResolver(static fn (): Route => $route);

        return $request;
    }

    private function snapshot(string $name): string
    {
        return json_encode(['memo' => ['name' => $name]], JSON_THROW_ON_ERROR);
    }
}

<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Feature\Middleware;

use Componist\Auth\Support\TwoFactorSession;
use Componist\Auth\Tests\TestCase;

class AppAuthenticateMiddlewareTest extends TestCase
{
    public function test_dashboard_redirects_to_two_factor_when_code_is_pending(): void
    {
        $this->enableTwoFactor();

        $user = $this->createUser();
        $this->userWithTwoFactorCode($user);
        $this->actingAs($user);

        $this->get(route('dashboard.index'))
            ->assertRedirect(route('componist.auth.twoFactorAuth'));
    }

    public function test_dashboard_redirects_to_two_factor_when_code_has_expired(): void
    {
        $this->enableTwoFactor();

        $user = $this->createUser();
        $this->userWithTwoFactorCode($user, '123456', now()->subMinute());
        $this->actingAs($user);

        $this->get(route('dashboard.index'))
            ->assertRedirect(route('componist.auth.twoFactorAuth'));
    }

    public function test_dashboard_redirects_to_verification_when_email_is_unverified(): void
    {
        $this->enableVerification();

        $user = $this->createUser(['email_verified_at' => null]);
        $this->actingAs($user);

        $this->get(route('dashboard.index'))
            ->assertRedirect(route('verification.notice'));
    }

    public function test_logout_is_allowed_without_completing_two_factor(): void
    {
        $this->enableTwoFactor();

        $user = $this->createUser();
        $this->userWithTwoFactorCode($user);
        $this->actingAs($user);

        $this->post(route('componist.auth.logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_dashboard_redirects_to_two_factor_when_session_is_not_confirmed(): void
    {
        $this->enableTwoFactor();

        $user = $this->createUser();
        $this->actingAs($user);

        $this->get(route('dashboard.index'))
            ->assertRedirect(route('componist.auth.twoFactorAuth'));

        $this->get(route('package.todo.liste'))
            ->assertRedirect(route('componist.auth.twoFactorAuth'));
    }

    public function test_dashboard_is_reachable_after_two_factor_session_confirmation(): void
    {
        $this->enableTwoFactor();

        $user = $this->createUser();
        $this->actingAs($user);
        TwoFactorSession::confirm($user->id);

        $this->get(route('dashboard.index'))->assertOk();
        $this->get(route('package.todo.liste'))->assertOk();
    }
}

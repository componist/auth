<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Feature;

use App\Models\User;
use Componist\Auth\Tests\TestCase;

class AuthRoutesTest extends TestCase
{
    public function test_login_route_is_accessible_for_guests(): void
    {
        $this->get(route('componist.auth.login'))
            ->assertOk();
    }

    public function test_register_route_is_accessible_when_enabled(): void
    {
        $this->get(route('componist.auth.register'))
            ->assertOk();
    }

    public function test_forgot_password_route_is_accessible(): void
    {
        $this->get(route('componist.auth.password.request'))
            ->assertOk();
    }

    public function test_logout_show_page_is_accessible_via_get(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->get(route('componist.auth.logout.show'))
            ->assertOk()
            ->assertSee('Abmelden');
    }

    public function test_logout_logs_user_out_via_post(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->post(route('componist.auth.logout'))
            ->assertRedirect(route('componist.auth.login'));

        $this->assertGuest();
    }

    public function test_authenticated_user_cannot_access_login_page(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->get(route('componist.auth.login'))
            ->assertRedirect(route('dashboard.index'));
    }

    public function test_two_factor_route_requires_authentication(): void
    {
        $this->enableTwoFactor();

        $this->get(route('componist.auth.twoFactorAuth'))
            ->assertRedirect(route('componist.auth.login'));
    }

    public function test_verification_notice_requires_authentication(): void
    {
        $this->enableVerification();

        $this->get(route('componist.auth.verification.notice'))
            ->assertRedirect(route('componist.auth.login'));
    }
}

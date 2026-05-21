<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Feature\Livewire;

use App\Models\User;
use Componist\Auth\Livewire\Auth\UserLoginController;
use Componist\Auth\Notifications\TwoFactorCode;
use Componist\Auth\Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Livewire;

class UserLoginControllerTest extends TestCase
{
    public function test_guest_can_render_login_component(): void
    {
        Livewire::test(UserLoginController::class)
            ->assertStatus(200)
            ->assertSee('Login');
    }

    public function test_login_with_valid_credentials_redirects_to_dashboard(): void
    {
        $user = $this->createUserWithPassword('secret-password');

        Livewire::test(UserLoginController::class)
            ->set('email', $user->email)
            ->set('password', 'secret-password')
            ->call('login')
            ->assertRedirect(route('dashboard.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_invalid_password_shows_error_and_allows_retry(): void
    {
        $user = $this->createUserWithPassword('correct-password');

        Livewire::test(UserLoginController::class)
            ->set('email', $user->email)
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors(['email'])
            ->assertSee('Login');

        $this->assertGuest();

        Livewire::test(UserLoginController::class)
            ->set('email', $user->email)
            ->set('password', 'correct-password')
            ->call('login')
            ->assertRedirect(route('dashboard.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_unknown_email_shows_error(): void
    {
        Livewire::test(UserLoginController::class)
            ->set('email', 'unknown@example.com')
            ->set('password', 'password')
            ->call('login')
            ->assertHasErrors(['email' => 'Ungültige Zugangsdaten.']);

        $this->assertGuest();
    }

    public function test_login_validates_required_fields(): void
    {
        Livewire::test(UserLoginController::class)
            ->set('email', '')
            ->set('password', '')
            ->call('login')
            ->assertHasErrors(['email', 'password']);
    }

    public function test_login_rate_limits_after_five_failed_attempts(): void
    {
        $user = $this->createUserWithPassword('correct-password');
        $key = Str::transliterate(Str::lower($user->email).'|'.request()->ip());
        $this->clearRateLimiter($key);

        $component = Livewire::test(UserLoginController::class)
            ->set('email', $user->email);

        for ($i = 0; $i < 5; $i++) {
            $component->set('password', 'wrong-password')->call('login');
        }

        $component
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors(['email']);
    }

    public function test_authenticated_user_is_redirected_from_login_page(): void
    {
        $user = $this->createUser();

        Livewire::actingAs($user)
            ->test(UserLoginController::class)
            ->assertRedirect(route('dashboard.index'));
    }

    public function test_login_redirects_to_two_factor_when_enabled(): void
    {
        $this->enableTwoFactor();
        Notification::fake();

        $user = $this->createUserWithPassword('password');

        Livewire::test(UserLoginController::class)
            ->set('email', $user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertRedirect(route('componist.auth.twoFactorAuth'));

        Notification::assertSentTo($user->fresh(), TwoFactorCode::class);
    }

    public function test_login_redirects_to_verification_when_enabled_and_email_unverified(): void
    {
        $this->enableVerification();
        Notification::fake();

        $user = User::factory()->unverified()->create([
            'password' => 'password',
        ]);

        Livewire::test(UserLoginController::class)
            ->set('email', $user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertRedirect(route('verification.notice'));
    }

    public function test_remember_me_is_passed_to_auth_attempt(): void
    {
        $user = $this->createUserWithPassword('password');

        Livewire::test(UserLoginController::class)
            ->set('email', $user->email)
            ->set('password', 'password')
            ->set('remember', true)
            ->call('login')
            ->assertRedirect(route('dashboard.index'));

        $this->assertAuthenticatedAs($user);
    }
}

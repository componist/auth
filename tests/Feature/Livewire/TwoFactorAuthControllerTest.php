<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Feature\Livewire;

use Componist\Auth\Livewire\Auth\TwoFactorAuthController;
use Componist\Auth\Notifications\TwoFactorCode;
use Componist\Auth\Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

class TwoFactorAuthControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->enableTwoFactor();
    }

    public function test_authenticated_user_can_render_two_factor_component(): void
    {
        $user = $this->createUser();

        Livewire::actingAs($user)
            ->test(TwoFactorAuthController::class)
            ->assertStatus(200)
            ->assertSee('Zwei-Faktor-Authentifizierung');
    }

    public function test_login_with_valid_code_redirects_to_dashboard(): void
    {
        $user = $this->createUser();
        $this->userWithTwoFactorCode($user, '123456');

        Livewire::actingAs($user)
            ->test(TwoFactorAuthController::class)
            ->set('twoFactorAuthCode', '123456')
            ->call('login')
            ->assertRedirect(route('dashboard.index'));

        $user->refresh();
        $this->assertNull($user->two_factor_code);
        $this->assertNull($user->two_factor_expires_at);
    }

    public function test_login_with_invalid_code_shows_error_and_allows_retry(): void
    {
        $user = $this->createUser();
        $this->userWithTwoFactorCode($user, '123456');

        Livewire::actingAs($user)
            ->test(TwoFactorAuthController::class)
            ->set('twoFactorAuthCode', '000000')
            ->call('login')
            ->assertSet('loginMessage', 'Ungültiger Code.');

        Livewire::actingAs($user)
            ->test(TwoFactorAuthController::class)
            ->set('twoFactorAuthCode', '123456')
            ->call('login')
            ->assertRedirect(route('dashboard.index'));
    }

    public function test_login_rejects_expired_code(): void
    {
        $user = $this->createUser();
        $this->userWithTwoFactorCode($user, '123456', now()->subMinute());

        Livewire::actingAs($user)
            ->test(TwoFactorAuthController::class)
            ->set('twoFactorAuthCode', '123456')
            ->call('login')
            ->assertSet('loginMessage', 'Der Code ist abgelaufen. Bitte fordere einen neuen Code an.');
    }

    public function test_generate_sends_new_notification_and_clears_input(): void
    {
        Notification::fake();

        $user = $this->createUser();

        Livewire::actingAs($user)
            ->test(TwoFactorAuthController::class)
            ->set('twoFactorAuthCode', '999999')
            ->call('generate')
            ->assertSet('twoFactorAuthCode', '')
            ->assertSet('loginMessage', null);

        Notification::assertSentTo($user->fresh(), TwoFactorCode::class);
    }

    public function test_clear_resets_code_and_validation_errors(): void
    {
        $user = $this->createUser();

        Livewire::actingAs($user)
            ->test(TwoFactorAuthController::class)
            ->set('twoFactorAuthCode', '12')
            ->call('login')
            ->assertHasErrors(['twoFactorAuthCode'])
            ->call('clear')
            ->assertSet('twoFactorAuthCode', '')
            ->assertHasNoErrors();
    }

    public function test_login_rate_limits_after_five_failed_attempts(): void
    {
        $user = $this->createUser();
        $this->userWithTwoFactorCode($user, '123456');

        $key = 'two-factor-login|'.$user->id;
        $this->clearRateLimiter($key);

        $component = Livewire::actingAs($user)->test(TwoFactorAuthController::class);

        for ($i = 0; $i < 6; $i++) {
            $component->set('twoFactorAuthCode', '000000')->call('login');
        }

        $component->assertHasErrors(['twoFactorAuthCode']);
    }

    public function test_unauthenticated_login_redirects_to_auth_login(): void
    {
        Livewire::test(TwoFactorAuthController::class)
            ->call('login')
            ->assertRedirect(route('login'));
    }
}

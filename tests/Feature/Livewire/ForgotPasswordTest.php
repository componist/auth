<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Feature\Livewire;

use App\Models\User;
use Componist\Auth\Livewire\Auth\ForgotPassword;
use Componist\Auth\Tests\TestCase;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Livewire;

class ForgotPasswordTest extends TestCase
{
    public function test_guest_can_render_forgot_password_component(): void
    {
        Livewire::test(ForgotPassword::class)
            ->assertStatus(200)
            ->assertSee('Passwort vergessen');
    }

    public function test_send_reset_link_sends_notification_for_existing_user(): void
    {
        Notification::fake();

        $user = $this->createUser(['email' => 'reset@example.com']);

        Livewire::test(ForgotPassword::class)
            ->set('email', $user->email)
            ->call('sendResetLink')
            ->assertSet('status', ForgotPassword::RESET_LINK_SENT_MESSAGE)
            ->assertHasNoErrors(['email']);

        Notification::assertSentTo($user, ResetPassword::class);

        $notification = Notification::sent($user, ResetPassword::class)->first();
        $this->assertNotNull($notification);
        $mail = $notification->toMail($user);
        $this->assertStringContainsString(route('password.reset', [
            'token' => $notification->token,
            'email' => $user->email,
        ], false), (string) $mail->actionUrl);
    }

    public function test_reset_link_url_uses_app_url_not_request_host(): void
    {
        Notification::fake();
        config(['app.url' => 'http://app.example.test']);

        $this->app->instance('request', Request::create(
            'http://evil-attacker.test/forgot-password',
            'POST',
            server: ['HTTP_HOST' => 'evil-attacker.test'],
        ));

        $user = $this->createUser(['email' => 'host-poison@example.com']);

        Livewire::test(ForgotPassword::class)
            ->set('email', $user->email)
            ->call('sendResetLink');

        $notification = Notification::sent($user, ResetPassword::class)->first();
        $this->assertNotNull($notification);
        $actionUrl = (string) $notification->toMail($user)->actionUrl;

        $this->assertStringStartsWith('http://app.example.test/', $actionUrl);
        $this->assertStringNotContainsString('evil-attacker.test', $actionUrl);
    }

    public function test_send_reset_link_shows_same_message_for_unknown_email(): void
    {
        Notification::fake();

        Livewire::test(ForgotPassword::class)
            ->set('email', 'unknown@example.com')
            ->call('sendResetLink')
            ->assertSet('status', ForgotPassword::RESET_LINK_SENT_MESSAGE)
            ->assertHasNoErrors(['email']);

        Notification::assertNothingSent();
    }

    public function test_send_reset_link_validates_email(): void
    {
        Livewire::test(ForgotPassword::class)
            ->set('email', 'not-an-email')
            ->call('sendResetLink')
            ->assertHasErrors(['email']);
    }

    public function test_send_reset_link_rate_limits_after_five_attempts(): void
    {
        Notification::fake();

        $email = 'throttle@example.com';
        $key = Str::transliterate(Str::lower($email).'|'.request()->ip());
        $this->clearRateLimiter($key);

        $component = Livewire::test(ForgotPassword::class)->set('email', $email);

        for ($i = 0; $i < 6; $i++) {
            $component->call('sendResetLink');
        }

        $component->assertHasErrors(['email']);
    }
}

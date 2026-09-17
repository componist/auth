<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Feature\Livewire;

use App\Models\User;
use Componist\Auth\Livewire\Auth\ResetPassword;
use Componist\Auth\Tests\TestCase;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Livewire;

class ResetPasswordTest extends TestCase
{
    public function test_guest_can_render_reset_password_component_with_token(): void
    {
        $user = $this->createUser();
        $token = Password::createToken($user);

        Livewire::test(ResetPassword::class, ['token' => $token])
            ->set('email', $user->email)
            ->assertStatus(200)
            ->assertSee('Neues Passwort festlegen')
            ->assertSet('email', $user->email)
            ->assertSet('token', $token);
    }

    public function test_reset_password_updates_password_and_redirects_to_login(): void
    {
        Event::fake([PasswordReset::class]);

        $user = $this->createUser();
        $previousRememberToken = $user->remember_token;
        $token = Password::createToken($user);

        Livewire::test(ResetPassword::class, ['token' => $token])
            ->set('email', $user->email)
            ->set('password', 'new-password-99')
            ->set('password_confirmation', 'new-password-99')
            ->call('resetPassword')
            ->assertRedirect(route('login'));

        $user->refresh();

        $this->assertTrue(Hash::check('new-password-99', $user->password));
        $this->assertNotSame($previousRememberToken, $user->remember_token);
        Event::assertDispatched(PasswordReset::class, function (PasswordReset $event) use ($user): bool {
            return (int) $event->user->getAuthIdentifier() === (int) $user->id;
        });
    }

    public function test_reset_password_with_invalid_token_shows_error(): void
    {
        $user = $this->createUser();

        Livewire::test(ResetPassword::class, ['token' => 'invalid-token'])
            ->set('email', $user->email)
            ->set('password', 'new-password-99')
            ->set('password_confirmation', 'new-password-99')
            ->call('resetPassword')
            ->assertHasErrors(['email' => ResetPassword::GENERIC_FAILURE_MESSAGE]);
    }

    public function test_reset_password_unknown_email_shows_same_generic_error(): void
    {
        Livewire::test(ResetPassword::class, ['token' => 'invalid-token'])
            ->set('email', 'unknown@example.com')
            ->set('password', 'new-password-99')
            ->set('password_confirmation', 'new-password-99')
            ->call('resetPassword')
            ->assertHasErrors(['email' => ResetPassword::GENERIC_FAILURE_MESSAGE]);
    }

    public function test_reset_password_rate_limits_after_five_attempts(): void
    {
        $email = 'throttle-reset@example.com';
        $key = Str::transliterate('password-reset|'.Str::lower($email).'|'.request()->ip());
        $this->clearRateLimiter($key);

        $component = Livewire::test(ResetPassword::class, ['token' => 'invalid-token'])
            ->set('email', $email)
            ->set('password', 'new-password-99')
            ->set('password_confirmation', 'new-password-99');

        for ($i = 0; $i < 6; $i++) {
            $component->call('resetPassword');
        }

        $component->assertHasErrors(['email']);
        $this->assertStringContainsString('Zu viele Versuche', $component->errors()->first('email'));
    }

    public function test_reset_password_validates_password_confirmation(): void
    {
        $user = $this->createUser();
        $token = Password::createToken($user);

        Livewire::test(ResetPassword::class, ['token' => $token])
            ->set('email', $user->email)
            ->set('password', 'new-password-99')
            ->set('password_confirmation', 'mismatch')
            ->call('resetPassword')
            ->assertHasErrors(['password']);
    }
}

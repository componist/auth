<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Feature\Livewire;

use App\Models\User;
use Componist\Auth\Livewire\Auth\ResetPassword;
use Componist\Auth\Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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
        $user = $this->createUser();
        $token = Password::createToken($user);

        Livewire::test(ResetPassword::class, ['token' => $token])
            ->set('email', $user->email)
            ->set('password', 'new-password-99')
            ->set('password_confirmation', 'new-password-99')
            ->call('resetPassword')
            ->assertRedirect(route('componist.auth.login'));

        $user->refresh();

        $this->assertTrue(Hash::check('new-password-99', $user->password));
    }

    public function test_reset_password_with_invalid_token_shows_error(): void
    {
        $user = $this->createUser();

        Livewire::test(ResetPassword::class, ['token' => 'invalid-token'])
            ->set('email', $user->email)
            ->set('password', 'new-password-99')
            ->set('password_confirmation', 'new-password-99')
            ->call('resetPassword')
            ->assertHasErrors(['email']);
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

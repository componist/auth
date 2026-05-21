<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Feature\Livewire;

use App\Models\User;
use Componist\Auth\Livewire\Auth\UserRegisterController;
use Componist\Auth\Tests\TestCase;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

class UserRegisterControllerTest extends TestCase
{
    public function test_guest_can_render_register_component(): void
    {
        Livewire::test(UserRegisterController::class)
            ->assertStatus(200)
            ->assertSee('Account anlegen');
    }

    public function test_register_creates_user_and_redirects_to_dashboard(): void
    {
        Event::fake([Registered::class]);

        Livewire::test(UserRegisterController::class)
            ->set('name', 'Test User')
            ->set('email', 'new-user@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register')
            ->assertRedirect(route('dashboard.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'new-user@example.com',
            'name' => 'Test User',
        ]);

        $this->assertAuthenticated();
        Event::assertDispatched(Registered::class);
    }

    public function test_register_validates_unique_email(): void
    {
        $existing = $this->createUser(['email' => 'taken@example.com']);

        Livewire::test(UserRegisterController::class)
            ->set('name', 'Another User')
            ->set('email', $existing->email)
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_register_validates_password_confirmation(): void
    {
        Livewire::test(UserRegisterController::class)
            ->set('name', 'Test User')
            ->set('email', 'user@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'different')
            ->call('register')
            ->assertHasErrors(['password']);
    }

    public function test_register_redirects_to_verification_when_enabled(): void
    {
        $this->enableVerification();
        Notification::fake();

        Livewire::test(UserRegisterController::class)
            ->set('name', 'Verify Me')
            ->set('email', 'verify@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register')
            ->assertRedirect(route('verification.notice'));
    }

    public function test_register_returns_404_when_feature_disabled(): void
    {
        config(['componist_auth.features.register' => false]);

        Livewire::test(UserRegisterController::class)
            ->assertStatus(404);
    }

    public function test_authenticated_user_is_redirected_from_register_page(): void
    {
        $user = $this->createUser();

        Livewire::actingAs($user)
            ->test(UserRegisterController::class)
            ->assertRedirect(route('dashboard.index'));
    }

    public function test_register_rate_limits_after_five_attempts(): void
    {
        $key = 'register|'.request()->ip();
        $this->clearRateLimiter($key);

        $component = Livewire::test(UserRegisterController::class);

        for ($i = 0; $i < 6; $i++) {
            $component
                ->set('name', 'User '.$i)
                ->set('email', "user{$i}@example.com")
                ->set('password', 'short')
                ->set('password_confirmation', 'short')
                ->call('register');
        }

        $component->assertHasErrors(['email']);
    }
}

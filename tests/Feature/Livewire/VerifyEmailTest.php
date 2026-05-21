<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Feature\Livewire;

use App\Models\User;
use Componist\Auth\Livewire\Auth\VerifyEmail as VerifyEmailComponent;
use Componist\Auth\Tests\TestCase;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

class VerifyEmailTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->enableVerification();
    }

    public function test_unverified_user_can_render_verify_email_component(): void
    {
        $user = User::factory()->unverified()->create();

        Livewire::actingAs($user)
            ->test(VerifyEmailComponent::class)
            ->assertStatus(200)
            ->assertSee('Bitte bestätigen Sie Ihre E-Mail-Adresse');
    }

    public function test_verified_user_is_redirected_from_notice_page(): void
    {
        $user = $this->createUser();

        Livewire::actingAs($user)
            ->test(VerifyEmailComponent::class)
            ->assertRedirect(route('dashboard.index'));
    }

    public function test_again_sends_verification_notification(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        Livewire::actingAs($user)
            ->test(VerifyEmailComponent::class)
            ->call('again');

        Notification::assertSentTo($user, VerifyEmailNotification::class);
    }

    public function test_again_is_rate_limited(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();
        $key = 'verify-email|'.$user->id;
        $this->clearRateLimiter($key);

        $component = Livewire::actingAs($user)->test(VerifyEmailComponent::class);

        for ($i = 0; $i < 4; $i++) {
            $component->call('again');
        }

        $component->call('again')->assertHasErrors(['email']);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        Livewire::test(VerifyEmailComponent::class)
            ->assertRedirect(route('login'));
    }
}

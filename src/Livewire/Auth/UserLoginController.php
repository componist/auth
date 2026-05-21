<?php

declare(strict_types=1);

namespace Componist\Auth\Livewire\Auth;

use Componist\Auth\Livewire\Concerns\RendersAuthView;
use Componist\Auth\Support\AuthView;
use Componist\Auth\Support\AuthenticatedUser;
use Componist\Auth\Support\ComponistAuthConfig;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

class UserLoginController extends Component
{
    use RendersAuthView;
    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|min:8|max:255')]
    public string $password = '';

    public bool $remember = false;

    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirect(route(ComponistAuthConfig::homeRoute()), navigate: true);

            return;
        }

        if (! app()->environment('production')) {
            $exampleEmail = config('componist_auth.login.example.email');
            $examplePassword = config('componist_auth.login.example.password');

            if (is_string($exampleEmail) && $exampleEmail !== '' && is_string($examplePassword) && $examplePassword !== '') {
                $this->email = $exampleEmail;
                $this->password = $examplePassword;
            }
        }
    }

    #[Title('Login')]
    public function render(): View
    {
        return $this->authView(AuthView::Login);
    }

    public function login(): void
    {
        $this->ensureIsNotRateLimited();

        /** @var array{email: string, password: string} $credentials */
        $credentials = $this->validate();

        if (! Auth::attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password']],
            $this->remember
        )) {
            RateLimiter::hit($this->throttleKey(), 1800);

            throw ValidationException::withMessages([
                'email' => 'Ungültige Zugangsdaten.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        session()->regenerate();

        $user = AuthenticatedUser::twoFactor();

        if (ComponistAuthConfig::verificationEnabled() && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();

            $this->redirect(route('verification.notice'), navigate: true);

            return;
        }

        if (ComponistAuthConfig::twoFactorEnabled()) {
            $user->generateTwoFactorCode();

            $this->redirect(route('componist.auth.twoFactorAuth'), navigate: true);

            return;
        }

        $this->redirect(route(ComponistAuthConfig::homeRoute()), navigate: true);
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => "Zu viele Versuche. Bitte warte {$seconds} Sekunden.",
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}

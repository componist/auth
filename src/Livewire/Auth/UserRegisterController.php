<?php

declare(strict_types=1);

namespace Componist\Auth\Livewire\Auth;

use Componist\Auth\Contracts\TwoFactorAuthenticatable;
use Componist\Auth\Livewire\Concerns\RendersAuthView;
use Componist\Auth\Support\AuthView;
use Componist\Auth\Support\ComponistAuthConfig;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;

class UserRegisterController extends Component
{
    use RendersAuthView;
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(): void
    {
        if (! ComponistAuthConfig::registerEnabled()) {
            abort(404);
        }

        if (Auth::check()) {
            $this->redirect(route(ComponistAuthConfig::homeRoute()), navigate: true);
        }
    }

    #[Title('Account anlegen')]
    public function render(): View
    {
        return $this->authView(AuthView::Register);
    }

    public function register(): void
    {
        $this->ensureIsNotRateLimited();
        RateLimiter::hit($this->throttleKey(), 60);

        /** @var array{name: string, email: string, password: string} $validated */
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ]);

        $userModel = ComponistAuthConfig::userModel();

        /** @var Authenticatable&TwoFactorAuthenticatable $user */
        $user = $userModel::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        event(new Registered($user));

        Auth::login($user);
        session()->regenerate();

        RateLimiter::clear($this->throttleKey());

        if (ComponistAuthConfig::verificationEnabled()) {
            $user->sendEmailVerificationNotification();

            $this->redirect(route('verification.notice'), navigate: true);

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
        return Str::transliterate('register|'.request()->ip());
    }
}

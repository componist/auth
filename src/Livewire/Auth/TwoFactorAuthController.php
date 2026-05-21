<?php

declare(strict_types=1);

namespace Componist\Auth\Livewire\Auth;

use Componist\Auth\Livewire\Concerns\RendersAuthView;
use Componist\Auth\Support\AuthView;
use Componist\Auth\Support\AuthenticatedUser;
use Componist\Auth\Support\ComponistAuthConfig;
use Componist\Auth\Traits\AddComponistAuthentication;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;

class TwoFactorAuthController extends Component
{
    use RendersAuthView;

    public string $twoFactorAuthCode = '';

    public ?string $loginMessage = null;

    public function mount(): void
    {
        if (! ComponistAuthConfig::twoFactorEnabled()) {
            abort(404);
        }
    }

    #[Title('Zwei-Faktor-Authentifizierung')]
    public function render(): View
    {
        return $this->authView(AuthView::TwoFactor);
    }

    public function updatedTwoFactorAuthCode(): void
    {
        $this->loginMessage = null;
    }

    public function login(): void
    {
        if (! Auth::check()) {
            Auth::logout();

            $this->redirect(route(ComponistAuthConfig::loginRoute()), navigate: true);

            return;
        }

        $this->ensureLoginIsNotRateLimited();

        /** @var array{twoFactorAuthCode: string} $validated */
        $validated = $this->validate([
            'twoFactorAuthCode' => ['required', 'digits:6'],
        ]);

        $user = AuthenticatedUser::twoFactor();

        if (! AddComponistAuthentication::twoFactorExpiresAt($user)?->isFuture()) {
            $this->loginMessage = 'Der Code ist abgelaufen. Bitte fordere einen neuen Code an.';

            return;
        }

        if (! AddComponistAuthentication::verifyTwoFactorCode($user, $validated['twoFactorAuthCode'])) {
            RateLimiter::hit($this->loginThrottleKey(), 900);

            $this->loginMessage = 'Ungültiger Code.';

            return;
        }

        RateLimiter::clear($this->loginThrottleKey());
        $user->resetTwoFactorCode();
        session()->regenerate();

        $this->redirect(route(ComponistAuthConfig::homeRoute()), navigate: true);
    }

    public function clear(): void
    {
        $this->twoFactorAuthCode = '';
        $this->loginMessage = null;
        $this->resetValidation();
    }

    public function generate(): void
    {
        if (! Auth::check()) {
            return;
        }

        $this->ensureGenerateIsNotRateLimited();

        AuthenticatedUser::twoFactor()->generateTwoFactorCode();
        $this->loginMessage = null;
        $this->twoFactorAuthCode = '';
    }

    protected function ensureLoginIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->loginThrottleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->loginThrottleKey());

        throw ValidationException::withMessages([
            'twoFactorAuthCode' => "Zu viele Versuche. Bitte warte {$seconds} Sekunden.",
        ]);
    }

    protected function ensureGenerateIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->generateThrottleKey(), 3)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->generateThrottleKey());

        throw ValidationException::withMessages([
            'twoFactorAuthCode' => "Neuer Code kann erst in {$seconds} Sekunden angefordert werden.",
        ]);
    }

    protected function loginThrottleKey(): string
    {
        return 'two-factor-login|'.(string) Auth::id();
    }

    protected function generateThrottleKey(): string
    {
        return 'two-factor-generate|'.(string) Auth::id();
    }
}

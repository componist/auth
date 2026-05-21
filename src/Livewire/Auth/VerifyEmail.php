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
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;

class VerifyEmail extends Component
{
    use RendersAuthView;

    public function mount(): void
    {
        if (! ComponistAuthConfig::verificationEnabled()) {
            abort(404);
        }

        if (! Auth::check()) {
            $this->redirect(route('componist.auth.login'), navigate: true);

            return;
        }

        if (AuthenticatedUser::twoFactor()->hasVerifiedEmail()) {
            $this->redirect(route(ComponistAuthConfig::homeRoute()), navigate: true);
        }
    }

    #[Title('E-Mail-Adresse bestätigen')]
    public function render(): View
    {
        return $this->authView(AuthView::VerifyEmail);
    }

    public function again(): void
    {
        if (! Auth::check()) {
            return;
        }

        if (RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
            $seconds = RateLimiter::availableIn($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => "Bitte warte {$seconds} Sekunden, bevor du erneut eine E-Mail anforderst.",
            ]);
        }

        RateLimiter::hit($this->throttleKey(), 60);

        AuthenticatedUser::twoFactor()->sendEmailVerificationNotification();
    }

    protected function throttleKey(): string
    {
        return 'verify-email|'.(string) Auth::id();
    }
}

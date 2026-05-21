<?php

declare(strict_types=1);

namespace Componist\Auth\Livewire\Auth;

use Componist\Auth\Livewire\Concerns\RendersAuthView;
use Componist\Auth\Support\AuthView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ForgotPassword extends Component
{
    use RendersAuthView;

    public const RESET_LINK_SENT_MESSAGE = 'Wenn diese E-Mail-Adresse bei uns registriert ist, erhältst du in Kürze einen Link zum Zurücksetzen deines Passworts.';

    #[Validate('required|email|max:255')]
    public string $email = '';

    public ?string $status = null;

    public function mount(): void
    {
        if (! config('componist_auth.features.resetPasswords', true)) {
            abort(404);
        }
    }

    #[Title('Passwort reset')]
    public function render(): View
    {
        return $this->authView(AuthView::ForgotPassword);
    }

    public function sendResetLink(): void
    {
        $this->ensureIsNotRateLimited();

        /** @var array{email: string} $validated */
        $validated = $this->validate();

        Password::sendResetLink([
            'email' => $validated['email'],
        ]);

        RateLimiter::hit($this->throttleKey(), 60);

        $this->status = self::RESET_LINK_SENT_MESSAGE;
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

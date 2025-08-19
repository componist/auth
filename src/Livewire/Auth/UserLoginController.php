<?php

namespace Componist\Auth\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

class UserLoginController extends Component
{
    #[Validate('required|email|min:5')]
    public string $email = '';

    #[Validate('required|string|min:8')]
    public string $password = '';

    public bool $remember = false;

    public function mount()
    {
        if (Auth::check()) {
            return redirect()->route(config('componist_auth.home'));
        }
    }

    #[Title('Login')]
    public function render()
    {
        return view('componistAuth::livewire.auth.login')
            ->extends(config('componist_auth.layouts-app'))
            ->section('content');
    }

    public function login()
    {
        $this->ensureIsNotRateLimited();

        $validate = $this->validate();

        if (Auth::attempt($validate, $this->remember)) {
            RateLimiter::clear($this->throttleKey());

             if (config('componist_auth.verification')) {
                if (! Auth::user()->hasVerifiedEmail()) {
                    Auth::user()->sendEmailVerificationNotification();

                    return redirect()->route('componist.auth.verification.notice');
                }
            }

            if (config('componist_auth.two-factor')) {
                Auth::user()->generateTwoFactorCode();

                return redirect()->route('componist.auth.twoFactorAuth');
            }

            session()->regenerate();

            return redirect()->route(config('componist_auth.home'));
        }

        RateLimiter::hit($this->throttleKey(), 1800); // 30 Min.

        throw ValidationException::withMessages([
            'email' => 'Ungültige Zugangsdaten.',
        ]);
    }

    protected function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
            return;
        }

        throw ValidationException::withMessages([
            // 'email' => 'Zu viele Versuche. Bitte warte '.RateLimiter::availableIn($this->throttleKey()).' Sekunden.',
            'email' => 'Zu viele Versuche. Bitte warte 30 Minuten und versuchen Sie es erneut.',
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::lower($this->email).'|'.request()->ip();
    }
}
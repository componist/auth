<?php

namespace Componist\Auth\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

class VerifyEmail extends Component
{
    public function mount()
    {
        if (Auth::user()->email_verified_at) {
            return redirect()->route(config('componist_auth.home'));
        }
    }

    #[Title('E-Mail-Adresse bestätigen')]
    public function render()
    {
        return view('componistAuth::livewire.auth.verify-email')
            ->extends(config('componist_auth.layouts-app'))
            ->section('content');
    }

    public function again()
    {
        Auth::user()->sendEmailVerificationNotification();
    }
}
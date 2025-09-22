<?php

namespace Componist\Auth\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TwoFactorAuthController extends Component
{
    #[Validate('required|digits:6')]
    public string|int $twoFactorAuthCode;

    public ?string $loginMessage = null;

    #[Title('Zwei-Faktor-Authentifizierung')]
    public function render()
    {
        return view('componistAuth::livewire.auth.two-factor-auth-controller')
            ->extends(config('componist_auth.layouts-app'))
            ->section('content');
    }

    public function login()
    {
        $validated = $this->validate();

        if (Auth::check()) {

            if (Auth::user()->two_factor_code === $validated['twoFactorAuthCode']) {

                Auth::user()->resetTwoFactorCode();

                return redirect()->route(config('componist_auth.home'));
            }

            $this->loginMessage = 'Ungültiger Code';

        } else {
            Auth::logout();

            return redirect()->route('componist.auth.login');
        }
    }

    public function clear()
    {
        $this->twoFactorAuthCode = '';
        $this->loginMessage = false;
    }

    public function generate()
    {
        Auth::user()->generateTwoFactorCode();
    }
}

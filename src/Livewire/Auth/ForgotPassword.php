<?php

namespace Componist\Auth\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ForgotPassword extends Component
{
    #[Validate('required|email|min:5')]
    public string $email = '';

    public ?string $status = null;

    #[Title('Passwort reset')]
    public function render()
    {
        return view('componistAuth::livewire.auth.forgot-password')
            ->extends(config('componist_auth.layouts-app'))
            ->section('content');
    }

    public function sendResetLink()
    {
        $validate = $this->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(['email' => $validate['email'], function () {
            return redirect()->route('componist.auth.password.reset');
        }]);

        $this->status = __($status);

        if ($status !== Password::RESET_LINK_SENT) {
            $this->email = '';
            $this->addError('email', __($status));
        }
    }
}

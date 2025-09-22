<?php

namespace Componist\Auth\Livewire\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ResetPassword extends Component
{
    #[Validate('required|email|min:5')]
    public string $email = '';

    #[Validate('required|string|confirmed|min:8')]
    public string $password = '';

    public string $password_confirmation = '';

    public string $token = '';

    public ?string $status = null;

    public function mount()
    {
        $this->email = Request::query('email', '');
        $this->token = Request::route('token');
    }

    #[Title('Neues Passwort festlegen')]
    public function render()
    {
        return view('componistAuth::livewire.auth.reset-password')
            ->extends(config('componist_auth.layouts-app'))
            ->section('content');
    }

    public function resetPassword()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('componist.auth.login')->with('status', __($status));
        }

        $this->addError('email', __($status));
    }
}

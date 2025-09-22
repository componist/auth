<?php

namespace Componist\Auth\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

class UserRegisterController extends Component
{
    #[Validate('required|string|min:5')]
    public string $name = '';

    #[Validate('required|email|min:5')]
    public string $email = '';

    #[Validate('required|string|confirmed|min:8')]
    public string $password = '';

    #[Validate('required|string|min:8')]
    public string $password_confirmation = '';

    public function mount()
    {
        if (Auth::check()) {
            return redirect()->route(config('componist_auth.home'));
        }
    }

    #[Title('Account anlegen')]
    public function render()
    {
        return view('componistAuth::livewire.auth.register')
            ->extends(config('componist_auth.layouts-app'))
            ->section('content');
    }

    public function register()
    {
        $validated = Validator::make([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ])->validate();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user)); // Für E-Mail-Bestätigung

        auth()->login($user);

        session()->flash('status', 'Bestätigungs-E-Mail wurde gesendet.');

        return redirect()->route('componist.auth.verification.notice');
    }
}

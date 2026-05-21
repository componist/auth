<?php

declare(strict_types=1);

namespace Componist\Auth\Livewire\Auth;

use Componist\Auth\Livewire\Concerns\RendersAuthView;
use Componist\Auth\Support\AuthView;
use Componist\Auth\Support\ComponistAuthConfig;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\Title;
use Livewire\Component;

class ResetPassword extends Component
{
    use RendersAuthView;

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $token = '';

    public ?string $status = null;

    public function mount(?string $token = null): void
    {
        if (! config('componist_auth.features.resetPasswords', true)) {
            abort(404);
        }

        $email = Request::query('email', '');
        $this->email = is_string($email) ? $email : '';

        $routeToken = Request::route('token');
        $this->token = $token ?? (is_string($routeToken) ? $routeToken : '');
    }

    #[Title('Neues Passwort festlegen')]
    public function render(): View
    {
        return $this->authView(AuthView::ResetPassword);
    }

    public function resetPassword(): void
    {
        $this->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
            'token' => ['required', 'string'],
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function (Model $user): void {
                $user->forceFill([
                    'password' => $this->password,
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            $this->redirect(route(ComponistAuthConfig::loginRoute()), navigate: true);

            return;
        }

        if (! is_string($status)) {
            $this->addError('email', 'Passwort konnte nicht zurückgesetzt werden.');

            return;
        }

        $this->addError('email', __($status));
    }
}

<?php

declare(strict_types=1);

namespace Componist\Auth\Livewire\Auth;

use Componist\Auth\Livewire\Concerns\RendersAuthView;
use Componist\Auth\Support\AuthView;
use Componist\Auth\Support\ComponistAuthConfig;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
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
                    'remember_token' => Str::random(60),
                ])->save();

                if (Schema::hasTable('sessions') && Schema::hasColumn('sessions', 'user_id')) {
                    DB::table('sessions')->where('user_id', $user->getKey())->delete();
                }

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', 'Dein Passwort wurde erfolgreich zurückgesetzt. Du kannst dich jetzt anmelden.');

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

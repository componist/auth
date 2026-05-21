<?php

declare(strict_types=1);

namespace Componist\Auth\Support;

enum AuthView: string
{
    case Login = 'componistAuth::livewire.auth.login';
    case Register = 'componistAuth::livewire.auth.register';
    case ForgotPassword = 'componistAuth::livewire.auth.forgot-password';
    case ResetPassword = 'componistAuth::livewire.auth.reset-password';
    case TwoFactor = 'componistAuth::livewire.auth.two-factor-auth-controller';
    case VerifyEmail = 'componistAuth::livewire.auth.verify-email';
}

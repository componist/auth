<?php

declare(strict_types=1);

namespace Componist\Auth\Http\Controllers;

use Componist\Auth\Support\ComponistAuthConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LogoutController
{
    public function __invoke(): RedirectResponse
    {
        Auth::guard('web')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route(ComponistAuthConfig::loginRoute());
    }
}

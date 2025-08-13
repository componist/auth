<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
     */

    'components' => [

    ],
    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
     */

    'livewire' => [
        'auth.user-register-controller' => Componist\Auth\Livewire\Auth\UserRegisterController::class,
        'auth.user-login-controller' => Componist\Auth\Livewire\Auth\UserLoginController::class,
        'auth.verify-email' => Componist\Auth\Livewire\Auth\VerifyEmail::class,
        'auth.two-factor-auth-controller' => Componist\Auth\Livewire\Auth\TwoFactorAuthController::class,
        'auth.forgot-password' => Componist\Auth\Livewire\Auth\ForgotPassword::class,
        'auth.reset-password' => Componist\Auth\Livewire\Auth\ResetPassword::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Components Prefix
    |--------------------------------------------------------------------------
     */

    'prefix' => '',

    /*
    |--------------------------------------------------------------------------
    | Third Party Asset Libraries
    |--------------------------------------------------------------------------
     */

    'assets' => [],

];
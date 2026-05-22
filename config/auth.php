<?php

use App\Models\User;
use Componist\Core\View\Components\GuestLayout;

return [

    /*
    |--------------------------------------------------------------------------
    | E-Mail Verification
    |--------------------------------------------------------------------------
     */

    'verification' => (bool) env('COMPONIST_AUTH_VERIFICATION', false),

    /*
    |--------------------------------------------------------------------------
    | Two Faktor Auth
    |--------------------------------------------------------------------------
     */
    'two-factor' => (bool) env('COMPONIST_AUTH_TWO_FACTOR', false),

    /*
    |--------------------------------------------------------------------------
    | Home Path
    |--------------------------------------------------------------------------
     */
    'home' => 'dashboard.index',

    /*
    |--------------------------------------------------------------------------
    | Route names
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'login' => 'componist.auth.login',
        'verification_notice' => 'componist.auth.verification.notice',
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout Frontend for Auth views (required)
    |--------------------------------------------------------------------------
    |
    | Blade component class used by all auth Livewire pages (section "content").
    | Must exist in the host app — default: Componist\Core GuestLayout.
    | See package README: "Layout-Komponente (layouts-app)".
     */
    'layouts-app' => GuestLayout::class,

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
     */
    'user_model' => User::class,

    'features' => [
        'register' => (bool) env('COMPONIST_AUTH_REGISTER', env('APP_ENV') !== 'production'),
        'resetPasswords' => (bool) env('COMPONIST_AUTH_RESET_PASSWORDS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Login for Examples Demos (never set credentials in production)
    |--------------------------------------------------------------------------
     */
    'login' => [
        'example' => [
            'email' => null,
            'password' => null,
        ],
    ],
];

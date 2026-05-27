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

    /*
    |--------------------------------------------------------------------------
    | SEO (Auth-Seiten)
    |--------------------------------------------------------------------------
    |
    | Auth-Flows sind standardmäßig noindex (Login, 2FA, Reset).
    | metaDescription pro Seite in den Blade-Views via auth-page.
     */
    'seo' => [
        'robots' => env('COMPONIST_AUTH_SEO_ROBOTS', 'noindex, nofollow'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logo (Auth Branding)
    |--------------------------------------------------------------------------
    |
    | path  — Datei unter public/ (z. B. "images/logo.svg") oder absolute URL
    | alt   — Alt-Text für <img> (Standard: APP_NAME)
    | href  — Optionaler Link (URL, Pfad ab / oder Named Route)
    | height — CSS-Höhe des Logos (z. B. 2.5rem, 40px)
    | show_brand_name — App-Name neben/unter dem Logo anzeigen
    | brand_name — Überschreibt APP_NAME in der Brand-Zeile
     */
    'logo' => [
        'path' => env('COMPONIST_AUTH_LOGO_PATH'),
        'alt' => env('COMPONIST_AUTH_LOGO_ALT'),
        'href' => env('COMPONIST_AUTH_LOGO_HREF'),
        'height' => env('COMPONIST_AUTH_LOGO_HEIGHT', '2.5rem'),
        'show_brand_name' => (bool) env('COMPONIST_AUTH_LOGO_SHOW_BRAND_NAME', true),
        'brand_name' => env('COMPONIST_AUTH_LOGO_BRAND_NAME'),
    ],
];

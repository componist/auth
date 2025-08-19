<?php

return [

    /*
    |--------------------------------------------------------------------------
    | E-Mail Verification
    |--------------------------------------------------------------------------
     */

    'verification' => false,
    /*
    |--------------------------------------------------------------------------
    | Two Faktor Auth
    |--------------------------------------------------------------------------
     */
     'two-factor' => false,
    /*
    |--------------------------------------------------------------------------
    | Home Path
    |--------------------------------------------------------------------------
    |
    | Here you may configure the path where users will get redirected during
    | authentication or password reset when the operations are successful
    | and the user is authenticated. You are free to change this value.
    |
    */
    'home' => 'dashboard.index',
    /*
    |--------------------------------------------------------------------------
    | Layout Frontend for Auth views
    |--------------------------------------------------------------------------
     */
    'layouts-app' => 'layouts.app',

    'features' => [
        'register' => true,
        'resetPasswords' => true,
    ],
];
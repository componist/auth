<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // markiert die E-Mail als verifiziert

    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::name('componist.auth.')->group(function () {
    Route::livewire('login', Componist\Auth\Livewire\Auth\UserLoginController::class)->name('login');

    if (config('componist_auth.features.resetPasswords')) {
        Route::livewire('/forgot-password', Componist\Auth\Livewire\Auth\ForgotPassword::class)->middleware('guest')->name('password.request');
        Route::livewire('/reset-password/{token}', Componist\Auth\Livewire\Auth\ResetPassword::class)->middleware('guest')->name('password.reset');
    }

    if (config('componist_auth.features.register')) {
        Route::livewire('register', Componist\Auth\Livewire\Auth\UserRegisterController::class)->name('register');
    }

    Route::get('logout', function () {
        Auth::logout();

        return redirect()->route('componist.auth.login');
    })->name('logout');

    // if(config('componist_auth.verification')){
    //     Route::get('email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    //         $request->fulfill(); // markiert die E-Mail als verifiziert

    //         return redirect('/dashboard');
    //     })->middleware(['auth', 'signed'])->name('componist.auth.verification.verify');
    // }

    Route::middleware('auth')->group(function () {

        if (config('componist_auth.verification')) {
            Route::livewire('email/verify', Componist\Auth\Livewire\Auth\VerifyEmail::class)->name('verification.notice');
        }

        if (config('componist_auth.two-factor')) {
            Route::livewire('two-factor-auth', Componist\Auth\Livewire\Auth\TwoFactorAuthController::class)->name('twoFactorAuth');
        }
    });
});

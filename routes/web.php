<?php

use Componist\Auth\Http\Controllers\LogoutController;
use Componist\Auth\Livewire\Auth\UserLoginController;
use Componist\Auth\Support\ComponistAuthConfig;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::livewire('login', UserLoginController::class)->name('login');
    Route::livewire('/forgot-password', Componist\Auth\Livewire\Auth\ForgotPassword::class)->name('password.request');
    Route::livewire('/reset-password/{token}', Componist\Auth\Livewire\Auth\ResetPassword::class)->name('password.reset');
});

Route::name('componist.auth.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::livewire('register', Componist\Auth\Livewire\Auth\UserRegisterController::class)->name('register');
    });

    Route::middleware('auth')->group(function (): void {
        Route::match(['get', 'post'], 'logout', LogoutController::class)->name('logout');
    });

    Route::middleware('auth')->group(function () {
        Route::livewire('email/verify', Componist\Auth\Livewire\Auth\VerifyEmail::class)->name('verification.notice');

        Route::get('email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill();

            return redirect()->route(ComponistAuthConfig::homeRoute());
        })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

        Route::livewire('two-factor-auth', Componist\Auth\Livewire\Auth\TwoFactorAuthController::class)->name('twoFactorAuth');
    });
});

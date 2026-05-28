<?php

use Componist\Auth\Http\Controllers\LogoutController;
use Componist\Auth\Livewire\Auth\ForgotPassword;
use Componist\Auth\Livewire\Auth\ResetPassword;
use Componist\Auth\Livewire\Auth\TwoFactorAuthController;
use Componist\Auth\Livewire\Auth\UserLoginController;
use Componist\Auth\Livewire\Auth\UserRegisterController;
use Componist\Auth\Livewire\Auth\VerifyEmail;
use Componist\Auth\Support\ComponistAuthConfig;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

Route::name('componist.auth.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('login', UserLoginController::class)->name('login');
        Route::get('forgot-password', ForgotPassword::class)->name('password.request');
        Route::get('reset-password/{token}', ResetPassword::class)->name('password.reset');
        Route::get('register', UserRegisterController::class)->name('register');
    });

    Route::middleware('auth')->group(function (): void {
        Route::get('email/verify', VerifyEmail::class)->name('verification.notice');

        Route::get('email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill();

            return redirect()->route(ComponistAuthConfig::homeRoute());
        })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

        Route::match(['get', 'post'], 'logout', LogoutController::class)->name('logout');

        Route::get('two-factor-auth', TwoFactorAuthController::class)->name('twoFactorAuth');
    });
});

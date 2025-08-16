<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


Route::name('componist.auth.')->group(function(){
    Route::get('login', Componist\Auth\Livewire\Auth\UserLoginController::class)->name('login');

    
    if(config('componist_auth.features.resetPasswords')){
        Route::get('/forgot-password', Componist\Auth\Livewire\Auth\ForgotPassword::class)->middleware('guest')->name('password.request');
        Route::get('/reset-password/{token}', Componist\Auth\Livewire\Auth\ResetPassword::class)->middleware('guest')->name('password.reset');
    }

    if(config('componist_auth.features.register')){
        Route::get('register', Componist\Auth\Livewire\Auth\UserRegisterController::class)->name('register');
    }
    
    Route::get('logout', function () {
        Auth::logout();

        return redirect()->route('componist.auth.login');
    })->name('logout');


    if(config('componist_auth.verification')){
        Route::get('email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill(); // markiert die E-Mail als verifiziert

            return redirect('/dashboard');
        })->middleware(['auth', 'signed'])->name('componist.auth.verification.verify');
    }

    Route::middleware('auth')->group(function () {

        if(config('componist_auth.verification')){
            Route::get('email/verify', Componist\Auth\Livewire\Auth\VerifyEmail::class)->name('verification.notice');
        }

        if(config('componist_auth.two-factor')){
            Route::get('two-factor-auth', Componist\Auth\Livewire\Auth\TwoFactorAuthController::class)->name('twoFactorAuth');
        }
    });
});
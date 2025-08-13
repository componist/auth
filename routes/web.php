<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('login', Componist\Auth\Livewire\Auth\UserLoginController::class)->name('login');
Route::get('/forgot-password', Componist\Auth\Livewire\Auth\ForgotPassword::class)->middleware('guest')->name('password.request');
Route::get('/reset-password/{token}', Componist\Auth\Livewire\Auth\ResetPassword::class)->middleware('guest')->name('password.reset');
Route::get('register', Componist\Auth\Livewire\Auth\UserRegisterController::class)->name('register');
Route::get('logout', function () {
    Auth::logout();

    return redirect()->route('index');
})->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('verify', Componist\Auth\Livewire\Auth\VerifyEmail::class)->name('verification.notice');

    Route::get('2fa', Componist\Auth\Livewire\Auth\TwoFactorAuthController::class)->name('twoFactorAuth');

});
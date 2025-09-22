<?php

declare(strict_types=1);

namespace Componist\Auth;

use Componist\Auth\Livewire\Auth\ForgotPassword;
use Componist\Auth\Livewire\Auth\ResetPassword;
use Componist\Auth\Livewire\Auth\TwoFactorAuthController;
use Componist\Auth\Livewire\Auth\UserLoginController;
use Componist\Auth\Livewire\Auth\UserRegisterController;
use Componist\Auth\Livewire\Auth\VerifyEmail;
use Componist\Auth\Middleware\TwoFactorMiddleware;
use Componist\Auth\Middleware\VerifyEmailMiddleware;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Route::group(['middleware' => ['web']], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'componistAuth');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Livewire::component('auth.register', UserLoginController::class);
        Livewire::component('auth.login', UserRegisterController::class);
        Livewire::component('auth.verify-email', VerifyEmail::class);
        Livewire::component('auth.two-factor-auth-controller', TwoFactorAuthController::class);
        Livewire::component('auth.forgot-password', ForgotPassword::class);
        Livewire::component('auth.reset-password', ResetPassword::class);

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('twofactor', TwoFactorMiddleware::class);
        $router->aliasMiddleware('verify', VerifyEmailMiddleware::class);

        $this->publishes([
            __DIR__.'/../config/auth.php' => config_path('componist_auth.php'),
        ], 'componist-auth-config');
    }
}

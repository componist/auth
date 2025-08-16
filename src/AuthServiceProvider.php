<?php

declare(strict_types=1);

namespace Componist\Auth;

use Livewire\Livewire;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Componist\Auth\Middleware\TwoFactorMiddleware;
use Componist\Auth\Middleware\VerifyEmailMiddleware;

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
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'componistAuth');

        Livewire::component('auth.register', \Componist\Auth\Livewire\Auth\UserLoginController::class);
        Livewire::component('auth.login', \Componist\Auth\Livewire\Auth\UserRegisterController::class);
        Livewire::component('auth.verify-email', \Componist\Auth\Livewire\Auth\VerifyEmail::class);
        Livewire::component('auth.two-factor-auth-controller', \Componist\Auth\Livewire\Auth\TwoFactorAuthController::class);
        Livewire::component('auth.forgot-password', \Componist\Auth\Livewire\Auth\ForgotPassword::class);
        Livewire::component('auth.reset-password', \Componist\Auth\Livewire\Auth\ResetPassword::class);
    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('twofactor', TwoFactorMiddleware::class);
        $router->aliasMiddleware('verify', VerifyEmailMiddleware::class);
        
        $this->publishes([
            __DIR__.'/../config/auth.php' => config_path('componist_auth.php'),
        ], 'componist-auth-config');
    }
}
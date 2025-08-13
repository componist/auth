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
        $this->mergeConfigFrom(__DIR__.'../../config/auth.php', 'authConfig');

        Route::group(['middleware' => ['web']], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'componistAuth');

        // Livewire::component('dynamic-api.index', Index::class);

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
        ]);

        // blade componente
        $this->bootBladeComponents();

        // livewire componente
        $this->bootLivewireComponents();

    }

    private function bootBladeComponents(): void
    {
        foreach (config('authConfig.components', []) as $alias => $component) {
            Blade::component(config('authConfig.prefix').$alias, $component);
        }
    }

    private function bootLivewireComponents(): void
    {
        foreach (config('authConfig.livewire', []) as $alias => $component) {
            Livewire::component(config('authConfig.prefix').$alias, $component);
        }
    }


}
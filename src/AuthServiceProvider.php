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
use Componist\Auth\Support\ComponistAuthConfig;
use Componist\Auth\Support\ComponistAuthRouteAliases;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/auth.php', 'componist_auth');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'componistAuth');

        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'componist-auth');

        $this->callAfterResolving(ViewFactory::class, function (ViewFactory $view): void {
            if (is_dir($vendorViews = resource_path('views/vendor/componistAuth'))) {
                $view->prependNamespace('componistAuth', $vendorViews);
            }
        });
    }

    public function boot(): void
    {
        Route::group(['middleware' => ['web']], function (): void {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        Authenticate::redirectUsing(
            fn (Request $request): string => route(ComponistAuthConfig::loginRoute()),
        );

        Livewire::component('auth.login', UserLoginController::class);
        Livewire::component('auth.register', UserRegisterController::class);
        Livewire::component('auth.verify-email', VerifyEmail::class);
        Livewire::component('auth.two-factor-auth-controller', TwoFactorAuthController::class);
        Livewire::component('auth.forgot-password', ForgotPassword::class);
        Livewire::component('auth.reset-password', ResetPassword::class);

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('twofactor', TwoFactorMiddleware::class);
        $router->aliasMiddleware('verify', VerifyEmailMiddleware::class);

        ResetPasswordNotification::createUrlUsing(
            fn (object $notifiable, string $token): string => route('componist.auth.password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]),
        );

        VerifyEmailNotification::createUrlUsing(
            fn (object $notifiable): string => URL::temporarySignedRoute(
                'componist.auth.verification.verify',
                Carbon::now()->addMinutes((int) Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ],
            ),
        );

        $this->publishes([
            __DIR__.'/../config/auth.php' => config_path('componist_auth.php'),
        ], 'componist.auth.publish.config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/componistAuth'),
        ], 'componist.auth.publish.views');

        $this->publishes([
            __DIR__.'/../resources/css/auth.css' => resource_path('css/vendor/componist-auth.css'),
            __DIR__.'/../resources/css/auth-theme.css' => resource_path('css/vendor/componist-auth-theme.css'),
        ], 'componist.auth.publish.assets');

        $this->app->booted(function (): void {
            URL::resolveMissingNamedRoutesUsing(
                fn (string $name, array $parameters, bool $absolute): ?string => ComponistAuthRouteAliases::resolve(
                    $name,
                    $parameters,
                    $absolute,
                ),
            );
        });
    }
}

<?php

declare(strict_types=1);

namespace Componist\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSecondaryAuthentication
{
    /**
     * @var list<string>
     */
    private const SKIP_ROUTE_NAMES = [
        'componist.auth.logout',
        'componist.auth.verification.notice',
        'componist.auth.verification.verify',
        'componist.auth.twoFactorAuth',
    ];

    /**
     * Only challenge components may skip secondary auth. Guest auth components
     * are omitted: Authenticated requests never need them, and listing them
     * allowed forged companions to bypass 2FA for mixed Livewire batches.
     *
     * @var list<string>
     */
    private const SKIP_LIVEWIRE_COMPONENTS = [
        'auth.two-factor-auth-controller',
        'auth.verify-email',
    ];

    /**
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        $verifyResponse = app(VerifyEmailMiddleware::class)->handle(
            $request,
            static fn (): Response => new HttpResponse('', HttpResponse::HTTP_OK),
        );

        if ($verifyResponse->isRedirect()) {
            return $verifyResponse;
        }

        return app(TwoFactorMiddleware::class)->handle($request, $next);
    }

    private function shouldSkip(Request $request): bool
    {
        $routeName = $request->route()?->getName();

        if (is_string($routeName) && in_array($routeName, self::SKIP_ROUTE_NAMES, true)) {
            return true;
        }

        if (is_string($routeName) && str_ends_with($routeName, 'livewire.update')) {
            return $this->isAuthChallengeLivewireUpdate($request);
        }

        if ($request->is('livewire/*') && ! $request->is('livewire/update')) {
            return true;
        }

        return false;
    }

    private function isAuthChallengeLivewireUpdate(Request $request): bool
    {
        $components = $request->input('components', []);

        if (! is_array($components) || $components === []) {
            return false;
        }

        foreach ($components as $component) {
            if (! is_array($component)) {
                return false;
            }

            $snapshot = $component['snapshot'] ?? null;
            if (! is_string($snapshot) || $snapshot === '') {
                return false;
            }

            $decoded = json_decode($snapshot, true);
            $name = is_array($decoded) ? ($decoded['memo']['name'] ?? null) : null;

            if (! is_string($name) || ! in_array($name, self::SKIP_LIVEWIRE_COMPONENTS, true)) {
                return false;
            }
        }

        return true;
    }
}

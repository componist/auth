<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Unit;

use Componist\Auth\Support\CanonicalUrl;
use Componist\Auth\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CanonicalUrlTest extends TestCase
{
    public function test_using_app_url_ignores_request_host(): void
    {
        config(['app.url' => 'http://app.example.test']);

        $this->app->instance('request', Request::create(
            'http://evil-attacker.test/forgot-password',
            'POST',
            server: ['HTTP_HOST' => 'evil-attacker.test'],
        ));

        URL::forceRootUrl('');

        $url = CanonicalUrl::usingAppUrl(
            fn (): string => route('componist.auth.password.reset', [
                'token' => 'tokentest',
                'email' => 'victim@example.com',
            ]),
        );

        $this->assertStringStartsWith('http://app.example.test/', $url);
        $this->assertStringNotContainsString('evil-attacker.test', $url);
        $this->assertStringContainsString('tokentest', $url);
        $this->assertStringContainsString('victim%40example.com', $url);
    }
}

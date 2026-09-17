<?php

declare(strict_types=1);

namespace Componist\Auth\Tests\Feature;

use Componist\Auth\Notifications\TwoFactorCode;
use Componist\Auth\Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;

class TwoFactorCodeMailTest extends TestCase
{
    public function test_two_factor_mail_uses_componist_mail_shell(): void
    {
        Config::set('componist_mail.brand_name', 'Componist Test');
        Config::set('app.url', 'http://app.example.test');

        Notification::fake();

        $user = $this->createUser(['name' => 'Reinhold Jesse']);
        $this->enableTwoFactor();
        $user->generateTwoFactorCode();

        Notification::assertSentTo($user, TwoFactorCode::class, function (TwoFactorCode $notification) use ($user): bool {
            $mail = $notification->toMail($user);
            $html = $mail->render();

            $this->assertStringContainsString('Componist Test', $html);
            $this->assertStringContainsString('#14b8a6', $html);
            $this->assertStringContainsString('Dein Zwei-Faktor-Code', $html);
            $this->assertStringContainsString('Hallo Reinhold Jesse', $html);
            $this->assertStringContainsString('Gültig bis:', $html);
            $this->assertStringContainsString('#f0fdfa', $html);
            $this->assertStringContainsString($notification->code, $html);

            return true;
        });
    }
}

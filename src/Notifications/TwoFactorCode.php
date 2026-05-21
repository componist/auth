<?php

declare(strict_types=1);

namespace Componist\Auth\Notifications;

use Componist\Auth\Contracts\TwoFactorAuthenticatable;
use Componist\Auth\Traits\AddComponistAuthentication;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class TwoFactorCode extends Notification
{
    use Queueable;

    public function __construct(
        public TwoFactorAuthenticatable $user,
        public string $code,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(TwoFactorAuthenticatable $notifiable): MailMessage
    {
        $expiresLabel = 'unbekannt';

        if ($notifiable instanceof Model) {
            $expiresAt = AddComponistAuthentication::twoFactorExpiresAt($notifiable);
            $expiresLabel = $expiresAt instanceof Carbon
                ? $expiresAt->format('H:i')
                : 'unbekannt';
        }

        $name = 'Nutzer';

        if ($notifiable instanceof Model) {
            $nameAttribute = $notifiable->getAttribute('name');
            $name = is_string($nameAttribute) && $nameAttribute !== '' ? $nameAttribute : 'Nutzer';
        }

        return (new MailMessage)
            ->subject('Zwei-Faktor-Code')
            ->greeting('Hallo '.$name.',')
            ->line('Dein Zwei-Faktor-Code lautet:')
            ->line($this->code)
            ->line('Gültig bis: '.$expiresLabel);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}

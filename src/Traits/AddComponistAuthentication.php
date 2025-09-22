<?php

namespace Componist\Auth\Traits;

use Componist\Auth\Notifications\TwoFactorCode;
use Illuminate\Support\Facades\Notification;

trait AddComponistAuthentication
{
    public function generateTwoFactorCode()
    {
        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(10);
        $this->save();

        Notification::send($this, new TwoFactorCode($this));
    }

    public function resetTwoFactorCode()
    {
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }
}

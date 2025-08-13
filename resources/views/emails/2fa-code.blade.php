<p>Hallo {{ $user->name }},</p>
<p>Dein Zwei-Faktor-Code lautet:</p>
<h2>{{ $user->two_factor_code }}</h2>
<p>Gültig bis: {{ $user->two_factor_expires_at->format('H:i') }}</p>

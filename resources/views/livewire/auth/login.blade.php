<x-componist-auth::auth-page
    title="Login"
    subtitle="Melde dich mit deinem Konto an."
    meta-description="Sichere Anmeldung bei {{ config('app.name') }}. Zugang zu deinem persönlichen Bereich mit E-Mail und Passwort."
>
    <x-componist-auth::auth-form wire:submit="login">
        <x-componist-auth::auth-input
            label="E-Mail"
            name="email"
            field="email"
            type="email"
            wire:model="email"
            autocomplete="email"
            wire:loading.attr="disabled"
            wire:target="login"
            required
        />

        <x-componist-auth::auth-password
            label="Passwort"
            name="password"
            field="password"
            wire:model="password"
            autocomplete="current-password"
            wire:loading.attr="disabled"
            wire:target="login"
            required
        />

        <x-componist-auth::auth-actions>
            <x-componist-auth::auth-button type="submit" loading-target="login" loading-text="Wir melden dich an …">
                Login
            </x-componist-auth::auth-button>
        </x-componist-auth::auth-actions>
    </x-componist-auth::auth-form>

    @if (config('componist_auth.features.resetPasswords'))
        <x-slot:footer>
            <x-componist-auth::auth-link :href="route('componist.auth.password.request')">
                Passwort vergessen
            </x-componist-auth::auth-link>
        </x-slot:footer>
    @endif
</x-componist-auth::auth-page>

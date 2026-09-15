<x-componist-auth::auth-page
    title="Account anlegen"
    subtitle="Lege in wenigen Schritten ein neues Konto an."
    meta-description="Registrierung bei {{ config('app.name') }} – erstelle dein Konto mit Name, E-Mail und sicherem Passwort."
>
    <x-componist-auth::auth-form wire:submit.prevent="register">
        <x-componist-auth::auth-input
            label="Name"
            name="name"
            field="name"
            type="text"
            placeholder="Dein Name"
            wire:model.live="name"
            autocomplete="name"
            required
        />

        <x-componist-auth::auth-input
            label="E-Mail"
            name="email"
            field="email"
            type="email"
            placeholder="name@firma.de"
            wire:model.live="email"
            autocomplete="email"
            required
        />

        <x-componist-auth::auth-password
            label="Passwort"
            name="password"
            field="password"
            placeholder="Mindestens 8 Zeichen"
            wire:model.live="password"
            autocomplete="new-password"
            required
        />

        @if ($password != null)
            <x-componist-auth::auth-password
                label="Passwort bestätigen"
                name="password_confirmation"
                field="password_confirmation"
                placeholder="Passwort wiederholen"
                wire:model.live="password_confirmation"
                autocomplete="new-password"
                required
            />
        @endif

        <x-componist-auth::auth-actions>
            <x-componist-auth::auth-button type="submit">
                Registrieren
            </x-componist-auth::auth-button>
        </x-componist-auth::auth-actions>
    </x-componist-auth::auth-form>

    <x-componist-auth::auth-errors :errors="$errors" />
    <x-componist-auth::auth-status />

    <x-slot:footer>
        <p>
            Bereits registriert?
            <x-componist-auth::auth-link :href="route('componist.auth.login')" class="text-slate-900 dark:text-white">
                Anmelden
            </x-componist-auth::auth-link>
        </p>
    </x-slot:footer>
</x-componist-auth::auth-page>

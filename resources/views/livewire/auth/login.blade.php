<x-componist-auth::auth-page
    title="Willkommen zurück"
    subtitle="Melde dich in deinem Arbeitsbereich an."
    meta-description="Sichere Anmeldung bei {{ config('app.name') }}. Zugang zu deinem persönlichen Bereich mit E-Mail und Passwort."
>
    <x-componist-auth::auth-status />

    <x-componist-auth::auth-form wire:submit="login">
        <x-componist-auth::auth-input
            label="E-Mail"
            name="email"
            field="email"
            type="email"
            placeholder="name@firma.de"
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
            placeholder="••••••••"
            wire:model="password"
            autocomplete="current-password"
            wire:loading.attr="disabled"
            wire:target="login"
            required
        >
            <x-slot:hint>
                @if (config('componist_auth.features.resetPasswords'))
                    <x-componist-auth::auth-link :href="route('componist.auth.password.request')">
                        Passwort vergessen?
                    </x-componist-auth::auth-link>
                @endif
            </x-slot:hint>
        </x-componist-auth::auth-password>

        <label class="flex items-center gap-2 text-xs text-slate-600 dark:text-slate-300">
            <input
                type="checkbox"
                wire:model="remember"
                wire:loading.attr="disabled"
                wire:target="login"
                class="rounded border-slate-300 text-teal-500 focus:ring-teal-500 dark:border-slate-600 dark:bg-slate-800"
            >
            Angemeldet bleiben
        </label>

        <x-componist-auth::auth-actions>
            <x-componist-auth::auth-button type="submit" loading-target="login" loading-text="Wir melden dich an …">
                Anmelden
            </x-componist-auth::auth-button>
        </x-componist-auth::auth-actions>
    </x-componist-auth::auth-form>

    @if (config('componist_auth.features.register'))
        <x-slot:footer>
            <p>
                Noch kein Konto?
                <x-componist-auth::auth-link :href="route('componist.auth.register')" class="text-slate-900 dark:text-white">
                    Account anlegen
                </x-componist-auth::auth-link>
            </p>
        </x-slot:footer>
    @endif
</x-componist-auth::auth-page>

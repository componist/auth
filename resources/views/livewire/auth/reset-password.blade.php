<x-componist-auth::auth-page
    title="Neues Passwort festlegen"
    subtitle="Wähle ein sicheres neues Passwort für dein Konto."
    meta-description="Neues Passwort für {{ config('app.name') }} festlegen. Gib ein starkes Passwort ein und bestätige es."
>
    <x-componist-auth::auth-form wire:submit="resetPassword">
        <input type="hidden" wire:model="email">

        <x-componist-auth::auth-password
            label="Neues Passwort"
            name="password"
            field="password"
            wire:model="password"
            autocomplete="new-password"
            required
        />

        <x-componist-auth::auth-password
            label="Passwort bestätigen"
            name="password_confirmation"
            field="password_confirmation"
            wire:model="password_confirmation"
            autocomplete="new-password"
            required
        />

        <x-componist-auth::auth-field-error field="email" />

        <x-componist-auth::auth-actions>
            <x-componist-auth::auth-button
                type="submit"
                loading-target="resetPassword"
                loading-text="Wird gespeichert …"
            >
                Zurücksetzen
            </x-componist-auth::auth-button>
        </x-componist-auth::auth-actions>
    </x-componist-auth::auth-form>

    <x-slot:footer>
        <x-componist-auth::auth-link :href="route('componist.auth.login')">
            Zurück zum Login
        </x-componist-auth::auth-link>
    </x-slot:footer>
</x-componist-auth::auth-page>

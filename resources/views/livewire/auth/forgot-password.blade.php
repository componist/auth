<x-componist-auth::auth-page
    title="Passwort vergessen"
    subtitle="Wir senden dir einen Link zum Zurücksetzen deines Passworts."
    meta-description="Passwort zurücksetzen bei {{ config('app.name') }}. Fordere einen sicheren Reset-Link per E-Mail an."
>
    @if ($status == null)
        <x-componist-auth::auth-form wire:submit="sendResetLink">
            <x-componist-auth::auth-input
                label="E-Mail-Adresse"
                name="email"
                field="email"
                type="email"
                wire:model="email"
                autocomplete="email"
                required
            />

            <x-componist-auth::auth-actions>
                <x-componist-auth::auth-button
                    type="submit"
                    loading-target="sendResetLink"
                    loading-text="Wird gesendet …"
                >
                    Link senden
                </x-componist-auth::auth-button>
            </x-componist-auth::auth-actions>
        </x-componist-auth::auth-form>
    @else
        <x-componist-auth::auth-alert variant="success" class="text-center">
            {{ $status }}
        </x-componist-auth::auth-alert>
    @endif

    <x-slot:footer>
        <x-componist-auth::auth-link :href="route('componist.auth.login')">
            Zurück zum Login
        </x-componist-auth::auth-link>
    </x-slot:footer>
</x-componist-auth::auth-page>

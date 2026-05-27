<x-componist-auth::auth-page
    title="Zwei-Faktor-Authentifizierung"
    subtitle="Gib den sechsstelligen Code aus deiner E-Mail ein."
    meta-description="Zwei-Faktor-Authentifizierung bei {{ config('app.name') }}. Gib den Sicherheitscode aus deiner E-Mail ein."
>
    <x-componist-auth::auth-form>
        <x-componist-auth::auth-otp
            id="2fa_code"
            name="2fa_code"
            field="twoFactorAuthCode"
            clear-action="clear"
            wire:model.live.debounce.300ms="twoFactorAuthCode"
            required
            autofocus
        />

        <x-componist-auth::auth-callout>
            Wir haben dir einen Code per E-Mail geschickt. Er ist 10 Minuten gültig.
        </x-componist-auth::auth-callout>

        @if ($loginMessage)
            <x-componist-auth::auth-alert variant="error" class="text-center">
                {{ $loginMessage }}
            </x-componist-auth::auth-alert>
        @endif

        <x-componist-auth::auth-actions>
            @if (strlen((string) $twoFactorAuthCode) >= 6)
                <x-componist-auth::auth-button
                    type="button"
                    wire:click="login"
                    loading-target="login"
                    loading-text="Bitte warten …"
                >
                    Code bestätigen
                </x-componist-auth::auth-button>
            @endif

            <x-componist-auth::auth-button-secondary
                type="button"
                wire:click="generate"
                loading-target="generate"
                loading-text="Wird gesendet …"
            >
                Neuen Code anfordern
            </x-componist-auth::auth-button-secondary>
        </x-componist-auth::auth-actions>
    </x-componist-auth::auth-form>
</x-componist-auth::auth-page>

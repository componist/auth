<x-componist-auth::auth-page
    title="E-Mail bestätigen"
    subtitle="Öffne den Bestätigungslink in deinem Posteingang, um fortzufahren."
    meta-description="E-Mail-Adresse bei {{ config('app.name') }} bestätigen. Prüfe dein Postfach und klicke den Bestätigungslink."
>
    <x-componist-auth::auth-centered>
        <x-componist-auth::auth-icon-badge>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
            </svg>
        </x-componist-auth::auth-icon-badge>

        <p class="max-w-xs text-sm leading-relaxed text-slate-400">
            Keine E-Mail erhalten? Prüfe den Spam-Ordner oder fordere eine neue Bestätigung an.
        </p>

        <x-componist-auth::auth-button-secondary
            type="button"
            wire:click="again"
            loading-target="again"
            loading-text="Wird gesendet …"
            class="!w-auto px-6"
        >
            E-Mail erneut senden
        </x-componist-auth::auth-button-secondary>
    </x-componist-auth::auth-centered>
</x-componist-auth::auth-page>

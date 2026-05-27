@props([
    'label' => 'Sicherheitscode',
    'field' => 'twoFactorAuthCode',
    'clearAction' => null,
])

@php
    $inputId = $attributes->get('id', 'auth_otp_code');
@endphp

<div>
    <label for="{{ $inputId }}" class="auth-label">{{ $label }}</label>
    <div class="relative">
        <input
            id="{{ $inputId }}"
            type="text"
            inputmode="numeric"
            pattern="[0-9]*"
            maxlength="6"
            autocomplete="one-time-code"
            {{ $attributes->class(['auth-control auth-control--otp']) }}
        />
        @if ($clearAction)
            <button
                type="button"
                wire:click="{{ $clearAction }}"
                class="absolute inset-y-0 right-0 flex w-10 items-center justify-center text-slate-500 transition hover:text-teal-400"
                aria-label="Code löschen"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            </button>
        @endif
    </div>
    <x-componist-auth::auth-field-error :field="$field" />
</div>

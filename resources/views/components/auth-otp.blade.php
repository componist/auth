@props([
    'label' => 'Sicherheitscode',
    'field' => 'twoFactorAuthCode',
    'clearAction' => null,
])

@php
    $inputId = $attributes->get('id', 'auth_otp_code');
@endphp

<div class="flex flex-col gap-1.5">
    <label for="{{ $inputId }}" class="text-xs font-medium text-slate-700 dark:text-slate-200">{{ $label }}</label>
    <div class="relative">
        <input
            id="{{ $inputId }}"
            type="text"
            inputmode="numeric"
            pattern="[0-9]*"
            maxlength="6"
            autocomplete="one-time-code"
            placeholder="000000"
            {{ $attributes->class([
                'block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 pr-11 text-center text-lg font-semibold tracking-[0.35em] text-slate-900 shadow-sm outline-none transition placeholder:text-slate-300 placeholder:tracking-[0.35em] focus:border-teal-500 focus:ring-2 focus:ring-teal-500/30 disabled:cursor-not-allowed disabled:opacity-60 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-600 dark:focus:border-teal-500',
            ]) }}
        />
        @if ($clearAction)
            <button
                type="button"
                wire:click="{{ $clearAction }}"
                class="absolute inset-y-0 right-0 flex w-10 items-center justify-center text-slate-400 transition hover:text-teal-600 dark:hover:text-teal-400"
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

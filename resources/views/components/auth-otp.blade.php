@props([
    'label' => 'Sicherheitscode',
    'field' => 'twoFactorAuthCode',
    'clearAction' => null,
])

@php
    use Componist\Auth\Support\ComponistAuthConfig;

    $inputId = $attributes->get('id', 'auth_otp_code');
    $charset = ComponistAuthConfig::twoFactorCodeCharset();
    $length = ComponistAuthConfig::twoFactorCodeLength();
    $digitsOnly = $charset === 'digits';
    $placeholder = $digitsOnly ? str_repeat('0', $length) : str_repeat('A', $length);
    $inputmode = $digitsOnly ? 'numeric' : 'text';
    $pattern = $digitsOnly ? '[0-9]*' : '[A-Za-z0-9]*';
    $autocompleteCasing = $digitsOnly ? '' : ' uppercase';
@endphp

<div class="flex flex-col gap-1.5">
    <label for="{{ $inputId }}" class="text-xs font-medium text-slate-700 dark:text-slate-200">{{ $label }}</label>
    <div class="relative">
        <input
            id="{{ $inputId }}"
            type="text"
            inputmode="{{ $inputmode }}"
            pattern="{{ $pattern }}"
            maxlength="{{ $length }}"
            autocomplete="one-time-code"
            placeholder="{{ $placeholder }}"
            {{ $attributes->class([
                \Componist\Core\Support\Ui::FIELD.' pr-11 text-center text-lg font-semibold tracking-[0.35em] placeholder:tracking-[0.35em] placeholder:text-slate-300 dark:placeholder:text-slate-600'.$autocompleteCasing,
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

@props([
    'label',
    'name' => null,
    'field' => null,
])

@php
    $inputId = $attributes->get('id') ?? $name;
    $errorField = $field ?? $name;
@endphp

<div>
    <label @if ($inputId) for="{{ $inputId }}" @endif class="auth-label">
        {{ $label }}
    </label>

    <div x-data="{ show: false }" class="relative">
        <input
            @if ($inputId) id="{{ $inputId }}" @endif
            @if ($name) name="{{ $name }}" @endif
            x-bind:type="show ? 'text' : 'password'"
            {{ $attributes->class(['auth-control pr-11']) }}
        />
        <button
            x-on:click="show = ! show"
            type="button"
            tabindex="-1"
            class="absolute inset-y-0 right-0 flex w-10 items-center justify-center rounded-r-xl text-slate-500 transition hover:text-teal-400"
            aria-label="Passwort anzeigen"
        >
            <template x-if="show">
                <svg class="h-5 w-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="m16.1 13.3-1.45-1.45q.225-1.175-.675-2.2-.9-1.025-2.325-.8L10.2 7.4q.425-.2.862-.3Q11.5 7 12 7q1.875 0 3.188 1.312Q16.5 9.625 16.5 11.5q0 .5-.1.938-.1.437-.3.862Zm3.2 3.15-1.45-1.4q.95-.725 1.688-1.588.737-.862 1.262-1.962-1.25-2.525-3.588-4.013Q14.875 6 12 6q-.725 0-1.425.1-.7.1-1.375.3L7.65 4.85q1.025-.425 2.1-.638Q10.825 4 12 4q3.775 0 6.725 2.087Q21.675 8.175 23 11.5q-.575 1.475-1.512 2.738Q20.55 15.5 19.3 16.45Zm.5 6.15-4.2-4.15q-.875.275-1.762.413Q12.95 19 12 19q-3.775 0-6.725-2.087Q2.325 14.825 1 11.5q.525-1.325 1.325-2.463Q3.125 7.9 4.15 7L1.4 4.2l1.4-1.4 18.4 18.4ZM5.55 8.4q-.725.65-1.325 1.425T3.2 11.5q1.25 2.525 3.587 4.012Q9.125 17 12 17q.5 0 .975-.062.475-.063.975-.138l-.9-.95q-.275.075-.525.112Q12.275 16 12 16q-1.875 0-3.188-1.312Q7.5 13.375 7.5 11.5q0-.275.037-.525.038-.25.113-.525Zm7.975 2.325ZM9.75 12.6Z"
                    />
                </svg>
            </template>
            <template x-if="!show">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path
                        d="M12 16q1.875 0 3.188-1.312Q16.5 13.375 16.5 11.5q0-1.875-1.312-3.188Q13.875 7 12 7q-1.875 0-3.188 1.312Q7.5 9.625 7.5 11.5q0 1.875 1.312 3.188Q10.125 16 12 16Zm0-1.8q-1.125 0-1.912-.788Q9.3 12.625 9.3 11.5t.788-1.913Q10.875 8.8 12 8.8t1.913.787q.787.788.787 1.913t-.787 1.912q-.788.788-1.913.788Zm0 4.8q-3.65 0-6.65-2.038-3-2.037-4.35-5.462 1.35-3.425 4.35-5.463Q8.35 4 12 4q3.65 0 6.65 2.037 3 2.038 4.35 5.463-1.35 3.425-4.35 5.462Q15.65 19 12 19Zm0-7.5Zm0 5.5q2.825 0 5.188-1.488Q19.55 14.025 20.8 11.5q-1.25-2.525-3.612-4.013Q14.825 6 12 6 9.175 6 6.812 7.487 4.45 8.975 3.2 11.5q1.25 2.525 3.612 4.012Q9.175 17 12 17Z"
                    />
                </svg>
            </template>
        </button>
    </div>

    @isset($error)
        {{ $error }}
    @elseif ($errorField)
        <x-componist-auth::auth-field-error :field="$errorField" />
    @endif
</div>

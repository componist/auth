@props([
    'label',
    'name' => null,
    'field' => null,
])

@php
    $inputId = $attributes->get('id') ?? $name;
    $errorField = $field ?? $name;
    $controlClass = 'block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/30 disabled:cursor-not-allowed disabled:opacity-60 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-teal-500';
@endphp

<div class="flex flex-col gap-1.5">
    <label
        @if ($inputId) for="{{ $inputId }}" @endif
        class="text-xs font-medium text-slate-700 dark:text-slate-200"
    >
        {{ $label }}
    </label>
    <input
        @if ($inputId) id="{{ $inputId }}" @endif
        @if ($name) name="{{ $name }}" @endif
        {{ $attributes->class([$controlClass]) }}
    />
    @isset($error)
        {{ $error }}
    @elseif ($errorField)
        <x-componist-auth::auth-field-error :field="$errorField" />
    @endif
</div>

@props([
    'label',
    'name' => null,
    'field' => null,
])

@php
    $inputId = $attributes->get('id') ?? $name;
    $errorField = $field ?? $name;
    $controlClass = \Componist\Core\Support\Ui::FIELD;
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

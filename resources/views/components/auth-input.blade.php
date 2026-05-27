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
    <input
        @if ($inputId) id="{{ $inputId }}" @endif
        @if ($name) name="{{ $name }}" @endif
        {{ $attributes->class(['auth-control']) }}
    />
    @isset($error)
        {{ $error }}
    @elseif ($errorField)
        <x-componist-auth::auth-field-error :field="$errorField" />
    @endif
</div>

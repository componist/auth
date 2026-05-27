@props([
    'variant' => 'info',
])

@php
    $variantClass = match ($variant) {
        'success' => 'auth-alert--success',
        'error' => 'auth-alert--error',
        default => 'auth-alert--info',
    };
@endphp

<div {{ $attributes->class(['auth-alert', $variantClass]) }} role="{{ $variant === 'error' ? 'alert' : 'status' }}">
    {{ $slot }}
</div>

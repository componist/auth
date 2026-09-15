@props([
    'variant' => 'info',
])

@php
    $variantClass = match ($variant) {
        'success' => 'border-teal-200 bg-teal-50 text-teal-800 dark:border-teal-800 dark:bg-teal-950/40 dark:text-teal-200',
        'error' => 'border-red-200 bg-red-50 text-red-800 dark:border-red-900 dark:bg-red-950/40 dark:text-red-200',
        default => 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-700 dark:bg-slate-800/60 dark:text-slate-200',
    };
@endphp

<div
    {{ $attributes->class(['rounded-lg border px-3 py-2.5 text-sm leading-relaxed', $variantClass]) }}
    role="{{ $variant === 'error' ? 'alert' : 'status' }}"
>
    {{ $slot }}
</div>

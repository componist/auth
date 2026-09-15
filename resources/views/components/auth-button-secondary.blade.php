@props([
    'loadingTarget' => null,
    'loadingText' => null,
])

<button
    {{ $attributes->class([
        'inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition',
        'hover:bg-slate-50 hover:text-slate-900',
        'focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-white',
        'dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700 dark:hover:text-white dark:focus:ring-offset-slate-900',
        'disabled:cursor-not-allowed disabled:opacity-60',
    ]) }}
    @if ($loadingTarget)
        wire:loading.attr="disabled"
        wire:target="{{ $loadingTarget }}"
    @endif
>
    @if ($loadingTarget && $loadingText)
        <span wire:loading.remove wire:target="{{ $loadingTarget }}">{{ $slot }}</span>
        <span wire:loading wire:target="{{ $loadingTarget }}">{{ $loadingText }}</span>
    @else
        {{ $slot }}
    @endif
</button>

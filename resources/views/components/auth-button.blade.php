@props([
    'loadingTarget' => null,
    'loadingText' => null,
])

<button
    {{ $attributes->class([
        'auth-btn-lift inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-teal-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-teal-500/25',
        'hover:bg-teal-600',
        'focus:outline-none focus:ring-2 focus:ring-teal-500/40 focus:ring-offset-2 focus:ring-offset-white',
        'dark:bg-teal-500 dark:hover:bg-teal-400 dark:focus:ring-offset-[#0f1623]',
        'disabled:cursor-not-allowed disabled:opacity-60 disabled:shadow-none',
    ]) }}
    @if ($loadingTarget)
        wire:loading.attr="disabled"
        wire:target="{{ $loadingTarget }}"
    @endif
>
    @if ($loadingTarget && $loadingText)
        <span wire:loading.remove wire:target="{{ $loadingTarget }}">{{ $slot }}</span>
        <span wire:loading wire:target="{{ $loadingTarget }}" class="inline-flex items-center justify-center gap-2">
            <span
                class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"
                role="status"
                aria-hidden="true"
            ></span>
            {{ $loadingText }}
        </span>
    @else
        {{ $slot }}
    @endif
</button>

@props([
    'loadingTarget' => null,
    'loadingText' => null,
])

<button
    {{ $attributes->class([
        'inline-flex w-full items-center justify-center gap-2 rounded-xl bg-teal-500 px-4 py-3 text-sm font-semibold text-white shadow-md shadow-teal-500/30 transition',
        'hover:bg-teal-600 hover:shadow-lg hover:shadow-teal-500/35',
        'focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-slate-900',
        'active:scale-[0.99]',
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

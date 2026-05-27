@props([
    'loadingTarget' => null,
    'loadingText' => null,
])

<button
    {{ $attributes->class(['auth-btn-secondary']) }}
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

@props([
    'subtitle' => null,
    'headingId' => 'auth-page-title',
])

<header {{ $attributes->class(['flex flex-col gap-1']) }}>
    <h1 id="{{ $headingId }}" class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">
        {{ $slot }}
    </h1>
    @if ($subtitle)
        <p class="text-xs leading-relaxed text-slate-500 dark:text-slate-400">{{ $subtitle }}</p>
    @endif
</header>

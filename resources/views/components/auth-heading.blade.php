@props([
    'subtitle' => null,
    'headingId' => 'auth-page-title',
])

<header {{ $attributes->class(['flex flex-col gap-2']) }}>
    <h1 id="{{ $headingId }}" class="text-[1.65rem] font-semibold leading-tight tracking-tight text-slate-900 dark:text-slate-100 sm:text-[1.75rem]">
        {{ $slot }}
    </h1>
    @if ($subtitle)
        <p class="text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ $subtitle }}</p>
    @endif
</header>

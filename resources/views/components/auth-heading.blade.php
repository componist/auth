@props([
    'subtitle' => null,
    'headingId' => 'auth-page-title',
])

<header {{ $attributes->class(['mb-6 sm:mb-8']) }}>
    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-400">Authentifizierung</p>
    <h1 id="{{ $headingId }}" class="mt-2 text-2xl font-semibold tracking-tight text-white sm:text-3xl">
        {{ $slot }}
    </h1>
    @if ($subtitle)
        <p class="mt-2 text-sm leading-relaxed text-slate-400 sm:mt-3">{{ $subtitle }}</p>
    @endif
</header>

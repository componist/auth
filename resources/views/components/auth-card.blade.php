@props([
    'headingId' => 'auth-page-title',
])

<article
    {{ $attributes->class([
        'rounded-2xl border border-slate-700/60 bg-slate-900/75 p-6 shadow-2xl shadow-black/40 backdrop-blur-xl sm:p-8',
        'ring-1 ring-white/5',
    ]) }}
    aria-labelledby="{{ $headingId }}"
>
    {{ $slot }}
</article>

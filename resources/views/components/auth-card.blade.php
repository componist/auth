@props([
    'headingId' => 'auth-page-title',
    'brand' => null,
])

<article
    {{ $attributes->class([
        'flex flex-col gap-6 rounded-xl border border-slate-900/10 bg-white px-8 py-9 shadow-[0_12px_40px_-12px_rgba(15,23,42,0.14)] sm:px-9 sm:py-10',
        'dark:border-[#222] dark:bg-[#121212] dark:shadow-[0_10px_40px_rgba(0,0,0,0.8)]',
    ]) }}
    aria-labelledby="{{ $headingId }}"
>
    {{ $slot }}
</article>

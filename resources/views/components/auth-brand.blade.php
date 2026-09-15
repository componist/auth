@props([
    'brand' => null,
    'tone' => 'default',
])

@php
    use Componist\Auth\Support\AuthBranding;

    $brandName = $brand ?? AuthBranding::brandName();
    $logoUrl = AuthBranding::logoUrl();
    $logoAlt = AuthBranding::logoAlt();
    $logoHref = AuthBranding::logoHref();
    $logoHeight = AuthBranding::logoHeight();
    $showBrandName = AuthBranding::showBrandName();
    $onBrand = $tone === 'on-brand';
@endphp

<header {{ $attributes->class(['flex items-center gap-2.5']) }}>
    @if ($logoUrl)
        @if ($logoHref)
            <a href="{{ $logoHref }}" class="inline-flex items-center" aria-label="{{ $logoAlt }}">
                <img
                    src="{{ $logoUrl }}"
                    alt="{{ $logoAlt }}"
                    class="w-auto"
                    style="height: {{ $logoHeight }};"
                    decoding="async"
                >
            </a>
        @else
            <img
                src="{{ $logoUrl }}"
                alt="{{ $logoAlt }}"
                class="w-auto"
                style="height: {{ $logoHeight }};"
                decoding="async"
            >
        @endif
    @else
        <div
            class="{{ $onBrand
                ? 'flex size-8 items-center justify-center rounded-lg bg-white/15 ring-1 ring-white/25'
                : 'flex size-8 items-center justify-center rounded-lg bg-teal-500 text-white' }}"
            aria-hidden="true"
        >
            <span class="{{ $onBrand ? 'size-3 rotate-45 rounded-[3px] bg-white' : 'size-3 rotate-45 rounded-[3px] bg-white' }}"></span>
        </div>
    @endif

    @if ($showBrandName)
        <p class="{{ $onBrand
            ? 'text-sm font-semibold tracking-tight text-white'
            : 'text-sm font-semibold tracking-tight text-slate-900 dark:text-white' }}">
            {{ $brandName }}
        </p>
    @endif
</header>

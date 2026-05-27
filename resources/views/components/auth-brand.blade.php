@props([
    'brand' => null,
])

@php
    use Componist\Auth\Support\AuthBranding;

    $brandName = $brand ?? AuthBranding::brandName();
    $logoUrl = AuthBranding::logoUrl();
    $logoAlt = AuthBranding::logoAlt();
    $logoHref = AuthBranding::logoHref();
    $logoHeight = AuthBranding::logoHeight();
    $showBrandName = AuthBranding::showBrandName();
@endphp

<header {{ $attributes->class(['auth-brand-bar']) }}>
    @if ($logoUrl)
        @if ($logoHref)
            <a href="{{ $logoHref }}" class="auth-brand-logo-link" aria-label="{{ $logoAlt }}">
                <img
                    src="{{ $logoUrl }}"
                    alt="{{ $logoAlt }}"
                    class="auth-brand-logo"
                    style="--auth-logo-height: {{ $logoHeight }};"
                    width="auto"
                    height="auto"
                    decoding="async"
                >
            </a>
        @else
            <img
                src="{{ $logoUrl }}"
                alt="{{ $logoAlt }}"
                class="auth-brand-logo"
                style="--auth-logo-height: {{ $logoHeight }};"
                width="auto"
                height="auto"
                decoding="async"
            >
        @endif
    @else
        <div class="auth-brand-mark" aria-hidden="true">
            <svg class="h-5 w-5 text-teal-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 12l8-4.5M12 12v9M12 12L4 7.5" />
            </svg>
        </div>
    @endif

    @if ($showBrandName)
        <p class="auth-brand-name">{{ $brandName }}</p>
    @endif
</header>

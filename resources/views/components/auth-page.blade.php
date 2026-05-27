@props([
    'title',
    'subtitle' => null,
    'brand' => null,
    'metaDescription' => null,
    'metaTitle' => null,
    'robots' => null,
    'canonical' => null,
])

@php
    $appName = config('app.name', 'Laravel');
    $documentTitle = $metaTitle ?? "{$title} · {$appName}";
    $description = $metaDescription ?? $subtitle ?? "Authentifizierung bei {$appName}.";
    $robotsContent = $robots ?? config('componist_auth.seo.robots', 'noindex, nofollow');
    $canonicalUrl = $canonical ?? url()->current();
    $locale = str_replace('_', '-', app()->getLocale());
@endphp

@push('meta')
    <title>{{ $documentTitle }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="robots" content="{{ $robotsContent }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <meta property="og:locale" content="{{ $locale }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $documentTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="{{ $appName }}">

    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $documentTitle }}">
    <meta name="twitter:description" content="{{ $description }}">
@endpush

<x-componist-auth::auth-shell :brand="$brand">
    <x-componist-auth::auth-card heading-id="auth-page-title">
        <x-componist-auth::auth-heading :subtitle="$subtitle" heading-id="auth-page-title">
            {{ $title }}
        </x-componist-auth::auth-heading>

        <div class="auth-page-content">
            {{ $slot }}
        </div>

        @isset($footer)
            <x-componist-auth::auth-footer>
                {{ $footer }}
            </x-componist-auth::auth-footer>
        @endisset
    </x-componist-auth::auth-card>
</x-componist-auth::auth-shell>

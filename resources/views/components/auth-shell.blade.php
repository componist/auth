@props([
    'brand' => null,
])

@php
    use Componist\Auth\Support\AuthBranding;
@endphp

<div {{ $attributes->class(['auth-dark-canvas relative min-h-screen text-slate-100']) }}>
    <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
        <div class="auth-panel-glow absolute -top-32 left-1/2 h-96 w-96 -translate-x-1/2 rounded-full bg-teal-500/15 blur-3xl"></div>
        <div class="auth-panel-glow absolute right-0 bottom-0 h-72 w-72 rounded-full bg-slate-600/20 blur-3xl" style="animation-delay: 2.5s;"></div>
        <div class="auth-grid-pattern absolute inset-0 opacity-70"></div>
    </div>

    <main class="auth-shell-main" id="auth-main">
        <div class="auth-shell-container auth-animate-in">
            <x-componist-auth::auth-brand :brand="$brand" />

            {{ $slot }}

            <footer class="auth-shell-footer">
                <p>&copy; {{ date('Y') }} {{ AuthBranding::brandName() }}</p>
            </footer>
        </div>
    </main>
</div>

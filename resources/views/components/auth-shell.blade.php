@props([
    'brand' => null,
])

@php
    use Componist\Auth\Support\AuthBranding;
@endphp

<div
    {{ $attributes->class([
        'relative min-h-screen overflow-hidden text-slate-900 transition-colors duration-500 dark:text-slate-100',
        'bg-[#f4f5f3] dark:bg-black',
    ]) }}
    x-data="{
        dark: document.documentElement.classList.contains('dark'),
        ready: false,
        toggleDark() {
            this.dark = !this.dark;
            document.documentElement.classList.toggle('dark', this.dark);
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
            window.dispatchEvent(new CustomEvent('auth-theme-change', { detail: { dark: this.dark } }));
        }
    }"
    x-init="$nextTick(() => { ready = true })"
>
    {{-- wire:ignore: Livewire-Morph darf Canvas nicht auf Default 300×150 zurücksetzen --}}
    <canvas
        wire:ignore
        data-auth-dot-field
        class="pointer-events-none absolute inset-0 z-0 h-full w-full"
        aria-hidden="true"
    ></canvas>

    <div
        class="pointer-events-none absolute inset-0 z-[1] bg-[radial-gradient(circle_at_center,rgba(244,245,243,0.72)_0%,rgba(244,245,243,0)_70%)] dark:bg-[radial-gradient(circle_at_center,rgba(0,0,0,0.78)_0%,rgba(0,0,0,0)_100%)]"
        aria-hidden="true"
    ></div>

    <button
        type="button"
        x-cloak
        x-show="ready"
        x-on:click="toggleDark()"
        class="absolute right-5 top-5 z-20 inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-full border border-slate-900/10 bg-white/90 text-slate-500 shadow-sm backdrop-blur-sm transition hover:border-teal-500/40 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500/40 dark:border-white/10 dark:bg-[#121212]/90 dark:text-slate-400 dark:hover:text-white"
        aria-label="Darstellung umschalten"
    >
        <span x-show="!dark" x-cloak>
            <x:component::icon.moon class="h-5 w-5" />
        </span>
        <span x-show="dark" x-cloak>
            <x:component::icon.sun class="h-5 w-5" />
        </span>
    </button>

    <main
        id="auth-main"
        class="relative z-10 flex min-h-screen flex-col items-center justify-center px-5 py-16"
        x-cloak
        x-show="ready"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-3"
        x-transition:enter-end="opacity-100 translate-y-0"
    >
        <div class="mb-8 flex justify-center">
            <x-componist-auth::auth-brand :brand="$brand" />
        </div>

        <div class="w-full max-w-[400px]">
            {{ $slot }}
        </div>

        <p class="mt-10 text-center text-[11.5px] text-slate-500 dark:text-slate-500">
            &copy; {{ date('Y') }} {{ AuthBranding::brandName() }}
        </p>
    </main>
</div>

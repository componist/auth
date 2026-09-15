@props([
    'brand' => null,
])

@php
    use Componist\Auth\Support\AuthBranding;
@endphp

<div
    {{ $attributes->class(['relative min-h-screen text-slate-900 dark:text-slate-100']) }}
    x-data="{
        dark: document.documentElement.classList.contains('dark'),
        toggleDark() {
            this.dark = !this.dark;
            document.documentElement.classList.toggle('dark', this.dark);
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        }
    }"
>
    <button
        type="button"
        x-on:click="toggleDark()"
        class="absolute right-4 top-4 z-10 inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-lg text-slate-500 transition hover:bg-white hover:text-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-500 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-teal-400"
        aria-label="Darstellung umschalten"
    >
        <span x-show="!dark">
            <x:component::icon.moon class="h-5 w-5" />
        </span>
        <span x-show="dark" x-cloak>
            <x:component::icon.sun class="h-5 w-5" />
        </span>
    </button>

    <main class="flex min-h-screen items-start justify-center px-4 py-10 sm:items-center sm:px-6 sm:py-12" id="auth-main">
        <div class="w-full max-w-3xl">
            {{ $slot }}

            <p class="mt-6 text-center text-xs text-slate-500 dark:text-slate-400">
                &copy; {{ date('Y') }} {{ AuthBranding::brandName() }}
            </p>
        </div>
    </main>
</div>

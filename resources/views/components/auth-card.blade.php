@props([
    'headingId' => 'auth-page-title',
    'brand' => null,
])

<article
    {{ $attributes->class([
        'grid overflow-hidden rounded-lg border border-slate-200 bg-white shadow-xl shadow-slate-900/5',
        'md:grid-cols-2 dark:border-slate-700 dark:bg-slate-900 dark:shadow-black/40',
    ]) }}
    aria-labelledby="{{ $headingId }}"
>
    <div
        class="relative hidden flex-col justify-between overflow-hidden bg-gradient-to-b from-teal-500 to-teal-800 p-8 text-white md:flex lg:p-10"
    >
        <div class="pointer-events-none absolute -right-24 -top-24 size-64 rounded-full bg-white/10 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-16 -left-10 size-40 rounded-full bg-teal-950/30 blur-2xl" aria-hidden="true"></div>

        <div class="relative">
            <x-componist-auth::auth-brand :brand="$brand" tone="on-brand" />
        </div>

        <div class="relative mt-auto space-y-3 pt-16">
            <h2 class="max-w-[16ch] text-[1.65rem] font-semibold leading-tight tracking-tight">
                Dein Arbeitsbereich. Klar strukturiert.
            </h2>
            <p class="max-w-xs text-sm leading-relaxed text-white/80">
                Packages, Tools und Einstellungen an einem Ort – sicher und übersichtlich.
            </p>
        </div>
    </div>

    <div class="flex flex-col justify-center gap-5 p-6 sm:p-8">
        <div class="md:hidden">
            <x-componist-auth::auth-brand :brand="$brand" />
        </div>

        {{ $slot }}
    </div>
</article>

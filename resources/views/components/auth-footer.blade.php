<footer {{ $attributes->class(['text-center text-xs text-slate-500 dark:text-slate-400']) }}>
    <div class="flex flex-col items-center gap-2 sm:flex-row sm:justify-center sm:gap-4">
        {{ $slot }}
    </div>
</footer>

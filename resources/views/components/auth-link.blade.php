<a {{ $attributes->class([
    'text-xs font-medium text-slate-500 underline-offset-4 transition hover:text-teal-600 hover:underline',
    'focus:outline-none focus:underline dark:text-slate-400 dark:hover:text-teal-400',
]) }}>
    {{ $slot }}
</a>

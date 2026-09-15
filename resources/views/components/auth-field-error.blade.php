@props([
    'field',
])

@error($field)
    <p {{ $attributes->class(['text-xs text-red-600 dark:text-red-400']) }} role="alert">{{ $message }}</p>
@enderror

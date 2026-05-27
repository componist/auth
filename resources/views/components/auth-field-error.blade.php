@props([
    'field',
])

@error($field)
    <p {{ $attributes->class(['auth-field-error']) }} role="alert">{{ $message }}</p>
@enderror

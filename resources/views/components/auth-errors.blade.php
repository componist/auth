@props([
    'errors',
])

@if ($errors->any())
    <x-componist-auth::auth-alert variant="error" {{ $attributes }}>
        <ul class="list-inside list-disc space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-componist-auth::auth-alert>
@endif

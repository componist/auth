@if (session()->has('status'))
    <x-componist-auth::auth-alert variant="success" {{ $attributes }}>
        {{ session('status') }}
    </x-componist-auth::auth-alert>
@endif

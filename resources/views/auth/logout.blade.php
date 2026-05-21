<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Abmelden') }} — {{ config('app.name') }}</title>
</head>

<body class="bg-slate-200 font-sans antialiased text-slate-900">
    <div class="flex items-center justify-center min-h-screen px-6">
        <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-sm">
            <h1 class="mb-4 text-xl font-semibold text-slate-800">{{ __('Abmelden') }}</h1>
            <p class="mb-6 text-slate-600">{{ __('Möchtest du dich wirklich abmelden?') }}</p>

            <form method="POST" action="{{ route('componist.auth.logout') }}" class="flex items-center gap-4">
                @csrf
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    {{ __('Ja, abmelden') }}
                </button>
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route(config('componist_auth.home')) }}"
                    class="text-sm text-slate-600 hover:text-slate-800">
                    {{ __('Abbrechen') }}
                </a>
            </form>
        </div>
    </div>
</body>

</html>

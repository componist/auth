@props([
    'buttonClass' => 'block w-full px-4 py-2 text-left text-sm leading-5 text-slate-700 transition duration-150 ease-in-out hover:bg-teal-50 hover:text-teal-800 focus:bg-teal-50 focus:text-teal-800 focus:outline-none dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-white',
])

<form method="POST" action="{{ route('componist.auth.logout') }}" {{ $attributes }}>
    @csrf
    <button type="submit" class="{{ $buttonClass }}">
        {{ $slot->isEmpty() ? __('Abmelden') : $slot }}
    </button>
</form>

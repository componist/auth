@props([
    'buttonClass' => 'block w-full px-4 py-2 text-left leading-5 text-slate-700 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 transition duration-150 ease-in-out',
])

<form method="POST" action="{{ route('componist.auth.logout') }}" {{ $attributes->except('buttonClass') }}>
    @csrf
    <button type="submit" class="{{ $buttonClass }}">
        {{ $slot->isEmpty() ? __('Abmelden') : $slot }}
    </button>
</form>

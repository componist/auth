<div class="h-screen">
    <div class="h-full container mx-auto flex items-center justify-center">
        <div class="w-96">
            <h1 class="mt-9 mb-7 font-bold text-4xl text-center">Login</h1>

            <form wire:submit="login">
                <div class="mb-5">
                    <x-form.lable>E-Mail</x-form.lable>
                    <input wire:model="email" type="email" class="block w-full py-2 px-5 bg-white rounded-lg">
                    @error('email')
                        <div class="text-red-600 text-xs mt-3">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <x-form.lable>Passwort</x-form.lable>

                    <div x-data="{ show: false }" class="relative">
                        <input wire:model="password" x-bind:type="show ? 'text' : 'password'"
                            class="block w-full py-2 px-5 bg-white rounded-lg">
                        <button x-on:click="show = ! show" type="button"
                            class="absolute top-0 bottom-0 right-2 flex items-center justify-center z-10 text-slate-300 hover:text-slate-500 cursor-pointer">
                            <template x-if="show">
                                <x-icon.visibility-off />
                            </template>
                            <template x-if="!show">
                                <x-icon.visibility />
                            </template>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-red-600 text-xs mt-3">{{ $message }}</div>
                    @enderror
                </div>

                {{-- @if ($errors->any())
                    <div class="text-sm text-red-500">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}

                <div x-data="{ login: false }" class="mt-7">

                    {{-- <template x-if="!login"> --}}
                    <button type="submit" {{-- @click.prevent="login=true, $wire.login()" --}}
                        class="px-7 py-3 rounded-full text-primary-900 bg-primary-500 hover:bg-primary-600 w-full uppercase  inline-block shadow-sm font-bold cursor-pointer">Login</button>
                    {{-- </template> --}}

                    {{-- <template x-if="login"> -
                <p class="animate-pulse text-center">Ihre Authentifizierungs-Mail wird versendet</p>
                </template> --}}
                </div>
            </form>

            <div class="mt-7 text-center">
                <a
                    href="{{ route('password.request') }}"class="text-slate-300 hover:text-slate-600 font-bold cursor-pointer">Passwort
                    vergessen</a>
            </div>

        </div>
    </div>
</div>

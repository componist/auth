<div class="h-screen">
    <div class="h-full container mx-auto flex items-center justify-center">
        <div class="w-96">

            <h1 class="mt-9 mb-7 font-bold text-4xl text-center">Account anlegen</h1>

            <form wire:submit.prevent="register" class="grid grid-cols-1 gap-3">

                <div>
                    <x-form.lable>Name</x-form.lable>
                    <input type="text" wire:model.live="name" placeholder=""
                        class="block w-full py-2 px-5 bg-white rounded-lg" />
                </div>

                <div>
                    <x-form.lable>E-Mail</x-form.lable>
                    <input type="email" wire:model.live="email" placeholder=""
                        class="block w-full py-2 px-5 bg-white rounded-lg" />
                </div>

                <div>
                    <x-form.lable>Passwort</x-form.lable>
                    <div x-data="{ show: false }" class="relative">
                        <input wire:model.live="password" x-bind:type="show ? 'text' : 'password'"
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
                </div>


                @if ($password != null)
                    <div>
                        <x-form.lable>Passwort bestätigen</x-form.lable>
                        <input type="password" wire:model.live="password_confirmation" placeholder="Passwort bestätigen"
                            class="block w-full py-2 px-5 bg-white rounded-lg" />
                    </div>
                @endif


                <button type="submit"
                    class="px-7 py-3 rounded-full text-primary-900 bg-primary-500 hover:bg-primary-600 uppercase mt-7 inline-block shadow-sm font-bold cursor-pointer">Registrieren</button>
            </form>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            @if (session()->has('status'))
                <div class="text-green-600">{{ session('status') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="h-screen">
    <div class="h-full container mx-auto flex items-center justify-center">

        <div class="w-96">

            <h1 class="mt-9 mb-7 font-bold text-4xl text-center">Zwei-Faktor-Authentifizierung</h1>

            <div class="mt-5">
                <x-form.lable>Code eingeben</x-form.lable>
                <div class="relative">
                    <input type="text" name="2fa_code" id="2fa_code" wire:model.live.debounce.300ms="twoFactorAuthCode"
                        class="mt-1 block w-full border border-primary-500 rounded p-2 focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white"
                        required autofocus>
                    <button @click.prevent="$wire.clear" type="button"
                        class="absolute top-0 bottom-0 right-2 flex items-center justify-center z-10 text-slate-300 hover:text-slate-500 cursor-pointer">
                        <x-icon.trash />
                    </button>
                </div>
                @error('twoFactorAuthCode')
                    <div class="text-red-600 text-xs mt-3">{{ $message }}</div>
                @enderror
            </div>
            <p class="text-sm text-slate-600 mt-3">
                Bitte gib den Code ein, den wir dir per E-Mail geschickt haben.
            </p>

            @if ($loginMessage)
                <div class="text-red-600 text-center">{{ $loginMessage }}</div>
            @endif

            <div class="grid grid-cols-1 gap-5 mt-7">
                @if ($twoFactorAuthCode && $loginMessage == null)
                    <div x-data="{ login: false }" class="mt-7">

                        <template x-if="!login">
                            <button type="button" @click.prevent="login=true, $wire.login()"
                                class="px-7 py-3 rounded-full text-primary-900 bg-primary-500 hover:bg-primary-600 w-full uppercase  inline-block shadow-sm font-bold cursor-pointer">Code
                                bestätigen</button>
                        </template>

                        <template x-if="login">
                            <p class="animate-pulse text-center">Bitte warten, Sie werden eingeloggt</p>
                        </template>
                    </div>
                @endif




                <button type="button" @click.prevent="$wire.generate"
                    class="text-slate-300 hover:text-slate-600 font-bold cursor-pointer">erneut senden</button>
            </div>

        </div>
    </div>
</div>

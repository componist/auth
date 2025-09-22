<div class="h-screen">
    <div class="container flex items-center justify-center h-full mx-auto">

        <div class="w-96">

            <h1 class="text-4xl font-bold text-center mt-9 mb-7">Neues Passwort festlegen</h1>

            <div class="grid grid-cols-1 gap-3">
                {{-- <div> --}}
                {{-- <label class="block mb-2 ml-2 text-sm font-medium text-slate-700">E-Mail-Adresse</label> --}}
                <input type="hidden" wire:model="email" class="block w-full px-5 py-2 bg-white rounded-lg" disabled>
                {{-- @error('email')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror --}}
                {{-- </div> --}}

                <div>
                    <label class="block mb-2 ml-2 text-sm font-medium text-slate-700">Neues Passwort</label>
                    <input type="password" wire:model="password" class="block w-full px-5 py-2 bg-white rounded-lg">
                    @error('password')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 ml-2 text-sm font-medium text-slate-700">Passwort bestätigen</label>
                    <input type="password" wire:model="password_confirmation"
                        class="block w-full px-5 py-2 bg-white rounded-lg">
                </div>
            </div>

            <div class="mt-7">
                <button @click.prevent="$wire.resetPassword()" type="button"
                    class="inline-block w-full py-3 font-bold uppercase rounded-full shadow-sm cursor-pointer px-7 text-dashboard-900 bg-dashboard-500 hover:bg-dashboard-600 mt-7">
                    Zurücksetzen
                </button>
            </div>
        </div>
    </div>
</div>

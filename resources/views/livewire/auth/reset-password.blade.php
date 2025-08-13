<div class="h-screen">
    <div class="h-full container mx-auto flex items-center justify-center">

        <div class="w-96">

            <h1 class="mt-9 mb-7 font-bold text-4xl text-center">Neues Passwort festlegen</h1>

            <div class="grid grid-cols-1 gap-3">
                <div>
                    <x-form.lable>E-Mail-Adresse</x-form.lable>
                    <input type="email" wire:model="email" class="block w-full py-2 px-5 bg-white rounded-lg" disabled>
                    @error('email')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <x-form.lable>Neues Passwort</x-form.lable>
                    <input type="password" wire:model="password" class="block w-full py-2 px-5 bg-white rounded-lg">
                    @error('password')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>

                    <x-form.lable>Passwort bestätigen</x-form.lable>
                    <input type="password" wire:model="password_confirmation"
                        class="block w-full py-2 px-5 bg-white rounded-lg">
                </div>
            </div>

            <div class="mt-7">
                <button @click.prevent="$wire.resetPassword()" type="button"
                    class="px-7 py-3 rounded-full text-primary-900 bg-primary-500 hover:bg-primary-600 w-full uppercase mt-7 inline-block shadow-sm font-bold cursor-pointer">
                    Zurücksetzen
                </button>
            </div>
        </div>
    </div>
</div>

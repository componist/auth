<div class="h-screen">
    <div class="container flex items-center justify-center h-full mx-auto">

        <div class="w-96">

            <h1 class="text-4xl font-bold text-center mt-9 mb-7">Neues Passwort festlegen</h1>

            <form wire:submit="resetPassword" class="grid grid-cols-1 gap-3">
                <input type="hidden" wire:model="email">

                <div>
                    <label for="password" class="block mb-2 ml-2 text-sm font-medium text-slate-700">Neues Passwort</label>
                    <input id="password" type="password" wire:model="password" autocomplete="new-password"
                        class="block w-full px-5 py-2 bg-white rounded-lg border border-transparent focus:border-dashboard-500 focus:outline-none focus:ring-2 focus:ring-dashboard-500/30">
                    @error('password')
                        <span class="block mt-3 text-xs text-red-600" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block mb-2 ml-2 text-sm font-medium text-slate-700">Passwort bestätigen</label>
                    <input id="password_confirmation" type="password" wire:model="password_confirmation" autocomplete="new-password"
                        class="block w-full px-5 py-2 bg-white rounded-lg border border-transparent focus:border-dashboard-500 focus:outline-none focus:ring-2 focus:ring-dashboard-500/30">
                </div>

                @error('email')
                    <span class="block text-xs text-red-600" role="alert">{{ $message }}</span>
                @enderror

                <div class="mt-4">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="resetPassword"
                        class="inline-block w-full py-3 font-bold uppercase rounded-full shadow-sm cursor-pointer px-7 text-dashboard-900 bg-dashboard-500 hover:bg-dashboard-600 disabled:opacity-60 disabled:cursor-not-allowed transition-opacity"
                    >
                        <span wire:loading.remove wire:target="resetPassword">Zurücksetzen</span>
                        <span wire:loading wire:target="resetPassword">Wird gespeichert …</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

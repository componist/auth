<div class="h-screen">
    <div class="container flex items-center justify-center h-full mx-auto">

        <div class="w-96">

            <h1 class="text-4xl font-bold text-center mt-9 mb-7">Passwort vergessen</h1>

            @if ($status == null)
                <form wire:submit="sendResetLink" class="space-y-5">
                    <div>
                        <label for="email" class="block mb-2 ml-2 text-sm font-medium text-slate-700">E-Mail-Adresse</label>
                        <input id="email" type="email" wire:model="email" autocomplete="email"
                            class="block w-full px-5 py-2 bg-white rounded-lg border border-transparent focus:border-dashboard-500 focus:outline-none focus:ring-2 focus:ring-dashboard-500/30"
                            required>
                        @error('email')
                            <span class="block mt-3 text-xs text-red-600" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="sendResetLink"
                            class="inline-block w-full py-3 font-bold uppercase rounded-full shadow-sm cursor-pointer px-7 text-dashboard-900 bg-dashboard-500 hover:bg-dashboard-600 disabled:opacity-60 disabled:cursor-not-allowed transition-opacity"
                        >
                            <span wire:loading.remove wire:target="sendResetLink">Link senden</span>
                            <span wire:loading wire:target="sendResetLink">Wird gesendet …</span>
                        </button>
                    </div>
                </form>
            @else
                <p class="text-center text-slate-600">{{ $status }}</p>
            @endif
        </div>
    </div>
</div>

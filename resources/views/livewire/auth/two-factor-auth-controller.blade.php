<div class="h-screen">
    <div class="container flex items-center justify-center h-full mx-auto">

        <div class="w-96">

            <h1 class="text-4xl font-bold text-center mt-9 mb-7">Zwei-Faktor-Authentifizierung</h1>

            <div class="mt-5">
                <label for="2fa_code" class="block mb-2 ml-2 text-sm font-medium text-slate-700">Code eingeben</label>
                <div class="relative">
                    <input type="text" name="2fa_code" id="2fa_code" wire:model.live.debounce.300ms="twoFactorAuthCode"
                        inputmode="numeric" pattern="[0-9]*" maxlength="6"
                        class="block w-full p-2 mt-1 bg-white border rounded border-dashboard-500 focus:outline-none focus:ring-2 focus:ring-dashboard-500"
                        required autofocus autocomplete="one-time-code">
                    <button wire:click="clear" type="button"
                        class="absolute top-0 bottom-0 z-10 flex items-center justify-center cursor-pointer right-2 text-slate-300 hover:text-slate-500"
                        aria-label="Code löschen">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                            </path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </button>
                </div>
                @error('twoFactorAuthCode')
                    <div class="mt-3 text-xs text-red-600" role="alert">{{ $message }}</div>
                @enderror
            </div>
            <p class="mt-3 text-sm text-slate-600">
                Bitte gib den Code ein, den wir dir per E-Mail geschickt haben.
            </p>

            @if ($loginMessage)
                <div class="mt-4 text-sm text-center text-red-600" role="alert">{{ $loginMessage }}</div>
            @endif

            <div class="grid grid-cols-1 gap-5 mt-7">
                @if (strlen((string) $twoFactorAuthCode) >= 6)
                    <button
                        type="button"
                        wire:click="login"
                        wire:loading.attr="disabled"
                        wire:target="login"
                        class="inline-block w-full py-3 font-bold uppercase rounded-full shadow-sm cursor-pointer px-7 text-dashboard-900 bg-dashboard-500 hover:bg-dashboard-600 disabled:opacity-60 disabled:cursor-not-allowed transition-opacity"
                    >
                        <span wire:loading.remove wire:target="login">Code bestätigen</span>
                        <span wire:loading wire:target="login">Bitte warten …</span>
                    </button>
                @endif

                <button type="button" wire:click="generate" wire:loading.attr="disabled" wire:target="generate"
                    class="font-bold cursor-pointer text-slate-300 hover:text-slate-600 disabled:opacity-60">
                    <span wire:loading.remove wire:target="generate">erneut senden</span>
                    <span wire:loading wire:target="generate">Wird gesendet …</span>
                </button>
            </div>

        </div>
    </div>
</div>

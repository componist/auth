<div class="h-screen">
    <div class="container flex items-center justify-center h-full mx-auto">

        <div class="w-96">

            <h1 class="text-4xl font-bold text-center mt-9 mb-7">Passwort vergessen</h1>

            @if ($status == null)
                <div x-data="{ show: false }">
                    <template x-if="!show">
                        <div>
                            <div>
                                <label class="block mb-2 ml-2 text-sm font-medium text-gray-700">E-Mail-Adresse</label>
                                <input type="email" wire:model.live.debounce.300ms="email"
                                    class="block w-full px-5 py-2 bg-white rounded-lg" required>
                                @error('email')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-7">
                                <button @click.prevent="show=true, $wire.sendResetLink()" type="button"
                                    class="inline-block w-full py-3 font-bold uppercase rounded-full shadow-sm cursor-pointer px-7 text-primary-900 bg-primary-500 hover:bg-primary-600">
                                    Link senden
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            @else
                <p class="text-center text-slate-600">{{ $status }}</p>
            @endif
        </div>
    </div>
</div>

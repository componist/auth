<div class="h-screen">
    <div class="h-full container mx-auto flex items-center justify-center">

        <div class="w-96">

            <h1 class="mt-9 mb-7 font-bold text-4xl text-center">Passwort vergessen</h1>

            @if ($status == null)
                <div x-data="{ show: false }">
                    <template x-if="!show">
                        <div>
                            <div>
                                <x-form.lable>E-Mail-Adresse</x-form.lable>
                                <input type="email" wire:model.live.debounce.300ms="email"
                                    class="block w-full py-2 px-5 bg-white rounded-lg" required>
                                @error('email')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-7">
                                <button @click.prevent="show=true, $wire.sendResetLink()" type="button"
                                    class="px-7 py-3 rounded-full text-primary-900 bg-primary-500 hover:bg-primary-600 w-full uppercase  inline-block shadow-sm font-bold cursor-pointer">
                                    Link senden
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            @else
                <p class="text-slate-600 text-center">{{ $status }}</p>
            @endif
        </div>
    </div>
</div>

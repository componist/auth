 <main>
     <div class="flex items-center justify-center h-screen">
         <div class="container px-5 mx-auto text-center">
             <h1 class="text-4xl font-bold text-center mt-9 mb-7">Bitte bestätigen Sie Ihre E-Mail-Adresse</h1>

             <button type="button" wire:click="again" wire:loading.attr="disabled" wire:target="again"
                 class="cursor-pointer text-slate-400 hover:text-dashboard-500 disabled:opacity-60">
                 <span wire:loading.remove wire:target="again">E-Mail erneut senden</span>
                 <span wire:loading wire:target="again">Wird gesendet …</span>
             </button>
         </div>
     </div>
 </main>

<form wire:submit="save" class="card grid min-w-0 gap-4 p-6">
    <x-heading variant="article">Colore di sfondo</x-heading>
    <p>
        Questo colore verrà usato come sfondo della tua grafica in alcune sezioni del sito. Scegli un colore che faccia
        risaltare bene la tua immagine. Non influenza in alcun modo la stampa o il colore dei capi su cui la grafica
        sarà acquistabile.
    </p>
    <div x-data="{ color: $wire.entangle('value') }" class="flex items-center gap-2">
        <label for="design-background-color">Scegli il colore:</label
        ><input
            id="design-background-color"
            class="bg-surface-500 border-border-400 h-8 w-10 cursor-pointer rounded-sm border p-0.5"
            type="color"
            x-model="color"
            @input="$dispatch('design-background-preview', { color: color })"
        /><span x-text="color" class="text-highlight uppercase"></span>
    </div>
    @error('value')
        <x-alert type="error">{{ $message }}</x-alert>
    @enderror
    <x-ecommerce.designs.edit.save-feedback :status="$saveStatus" />
</form>

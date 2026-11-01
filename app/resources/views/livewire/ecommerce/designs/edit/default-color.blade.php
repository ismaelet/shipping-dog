<form wire:submit="save" class="card grid min-w-0 grid-cols-1 gap-4 p-6">
    <x-heading variant="article">Colori</x-heading>
    <p>
        Qui puoi selezionare il colore del capo che sarà mostrato come prima scelta in tutte le anteprime. Non influenza
        i capi che non hanno disponibile il colore scelto.
    </p>
    <div class="mt-2 flex flex-wrap gap-2">
        @foreach ($colors as $color)
            <x-ecommerce.designs.edit.color-swatch :color="$color">
                <input
                    type="radio"
                    class="sr-only"
                    name="design-default-color"
                    value="{{ $color->id }}"
                    wire:model="value"
                    aria-label="{{ $color->name }}"
            /></x-ecommerce.designs.edit.color-swatch>
        @endforeach
    </div>

    @error('value')
        <x-alert type="error">{{ $message }}</x-alert>
    @enderror

    <x-ecommerce.designs.edit.save-feedback :status="$saveStatus" label="Applica a TUTTI i capi" />
</form>

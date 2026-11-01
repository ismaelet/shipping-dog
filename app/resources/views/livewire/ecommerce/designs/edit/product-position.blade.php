<form
    wire:submit="save"
    class="grid gap-8 lg:grid-cols-2"
    x-data="{
        size: $wire.entangle('size'),
        x: $wire.entangle('posX'),
        y: $wire.entangle('posY'),
    }"
>
    @if ($config)
        <x-ecommerce.designs.edit.position-preview :$product :$config :$clothing :$details :$light :$dark :$landscape />
    @else
        <p>Anteprima non disponibile: configurazione di stampa mancante.</p>
    @endif
    <div class="grid content-start gap-6">
        <x-heading variant="article">Stampa in posizione {{ strtolower($position->name) }}</x-heading>
        <p>{{ $position->info }}</p>
        <p>
            Puoi anche abilitare o disabilitare la possibilità di acquistare questa versione del prodotto usando il
            bottone qui sotto.
        </p>
        <x-button
            type="button"
            size="sm"
            variant="secondary"
            wire:click="toggle"
            wire:loading.attr="disabled"
        >{{ $enabled ? 'Disattiva posizione' : 'Attiva posizione' }}</x-button>
        @error('position')
            <x-alert type="error">{{ $message }}</x-alert>
        @enderror
        @if ($config)
            <fieldset @disabled(! $enabled) class="grid gap-4">
                <label class="grid gap-2"
                    >Dimensione<x-input type="range" min="25" max="100" step="1" x-model="size" class="w-full"
                /></label>
                <label class="grid gap-2"
                    >Posizione verticale<x-input type="range" min="0" max="100" step="5" x-model="y" class="w-full"
                /></label>
                <label class="grid gap-2"
                    >Posizione orizzontale<x-input type="range" min="0" max="100" step="5" x-model="x" class="w-full"
                /></label>
                @foreach (['size', 'posX', 'posY'] as $field)
                    @error($field)
                        <x-alert type="error">{{ $message }}</x-alert>
                    @enderror
                @endforeach
                <x-ecommerce.designs.edit.save-feedback :status="$saveStatus" />
            </fieldset>
        @endif
    </div>
</form>

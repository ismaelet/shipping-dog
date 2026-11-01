<form wire:submit="save" class="grid grid-cols-1 gap-8 lg:grid-cols-2">
    {{-- Preview --}}
    <x-ecommerce.designs.edit.product-colors.preview :image="$this->previewImage" :product-id="$productId" />

    <div class="grid items-start">
        <div class="grid gap-8">
            <x-heading variant="article" class="">Seleziona i colori disponibili</x-heading>

            <p>
                Qui puoi selezionare i vari colori disponibili per questo capo e il colore principale, che verrà
                mostrato in tutte le anteprime.
            </p>

            <div class="grid gap-8">
                {{-- Active Colors --}}
                <x-ecommerce.designs.edit.product-colors.active-colors
                    :colors="$this->colors"
                    :product-id="$productId"
                />

                {{-- Default Color --}}
                <x-ecommerce.designs.edit.product-colors.default-color
                    :colors="$this->colors"
                    :product-id="$productId"
                    :active-color-ids="$activeColorIds"
                />

                {{-- Save button --}}
                <x-ecommerce.designs.edit.save-feedback :status="$saveStatus" />
            </div>
        </div>
    </div>
</form>

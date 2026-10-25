<div class="{{ count($positions) < 2 || $product->isCustom() ? 'hidden' : '' }}">
    <x-product.options.label>Posizione della stampa</x-product.options.label>

    <div class="product-options columns-[10rem]">
        @foreach ($positions as $key => $position)
            <label class="product-option">
                <input
                    type="radio"
                    name="position_id"
                    value="{{ $position->id }}"
                    wire:model.live="selectedPositionId"
                    @checked($position->id === $selectedPositionId)
                />

                {{ $position->name }}
            </label>
        @endforeach
    </div>
</div>

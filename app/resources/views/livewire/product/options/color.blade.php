@props(['product'])

@pushOnce('styles')
    <style>
        .color-option-wrapper:has(input:checked) {
            border-color: var(--color-primary-400);
        }
    </style>
@endpushOnce

<div>
    <x-product.options.label class="">Colore</x-product.options.label>

    <div class="flex flex-wrap gap-2">
        @foreach ($product->colors as $color)
            <label
                class="color-option-wrapper border-border-400 hover:border-primary-400 cursor-pointer rounded-full border-2 p-1 transition-colors"
                title="{{ $color->name }}"
            >
                <input
                    type="radio"
                    name="color_id"
                    class="sr-only"
                    value="{{ $color->id }}"
                    @checked($product->color?->id ? $color->id == $product->color->id : $loop->first)
                    wire:model.live="selectedColorId"
                />

                <div
                    class="color-option-primary size-5.5 rounded-full shadow-lg/20"
                    style="background-color: {{ $color->primary_color_hex }}"
                >
                    @if ($color->secondary_color_hex)
                        <span
                            class="color-option-secondary"
                            style="background-color: {{ $color->secondary_color_hex }}"
                        ></span>
                    @endif
                </div>
            </label>
        @endforeach
    </div>
</div>

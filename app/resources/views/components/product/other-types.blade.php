@if (! $product->isCustom())
    <section id="type-sec" class="py-14 max-sm:py-10">
        <div class="mb-6">
            <x-eyebrow> Lo stesso design, altri prodotti </x-eyebrow>
            <x-heading variant="section-small">Disponibile anche su</x-heading>
        </div>

        <div class="grid grid-cols-1 gap-12">
            @if ($relatedGarments->count())
                <div class="grid grid-cols-2 gap-8 sm:columns-[15rem]">
                    @foreach ($relatedGarments->take(4) as $garment)
                        <x-product-card :product="$garment" />
                    @endforeach
                </div>
            @endif

            @if ($relatedAccessories->count())
                <div class="grid grid-cols-2 gap-8 sm:columns-[15rem]">
                    @foreach ($relatedAccessories->take(4) as $accessory)
                        <x-product-card :product="$accessory" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

@endif

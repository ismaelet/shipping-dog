<section id="type-sec" class="py-14 max-sm:py-10">
    <div class="mb-6">
        <x-eyebrow> Lo stesso design, altri prodotti </x-eyebrow>
        <x-heading variant="section-small">Disponibile anche su</x-heading>
    </div>

    <div class="grid grid-cols-1 gap-12">
        <div class="grid grid-cols-2 gap-8 sm:columns-[15rem]">
            @foreach ($productsWithSameDesign as $garment)
                <x-product-card :product="$garment" />
            @endforeach
        </div>
    </div>
</section>

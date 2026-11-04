<section id="type-sec" class="py-14 max-sm:py-10">
    <div class="mb-6">
        <x-eyebrow> Lo stesso design, altri prodotti </x-eyebrow>
        <x-heading variant="section-small">Disponibile anche su</x-heading>
    </div>

    <div class="grid grid-cols-1 gap-12">
        <div class="grid grid-cols-2 gap-4 sm:columns-[15rem] sm:gap-8">
            @foreach ($productsWithSameDesign as $garment)
                <x-product-card :product="$garment" />
            @endforeach

            <a
                href="{{ route('seller.show', ['seller' => $product->seller, 'design' => $product->design_id]) }}"
                class="group bg-primary-400 relative aspect-4/5 overflow-hidden rounded-3xl p-3 text-white sm:p-6"
            >
                <div class="flex h-full flex-col justify-between">
                    <x-icon-spark class="size-5 sm:size-9" />

                    <div>
                        <p class="text-xs text-white/70 sm:text-sm">{{ $product->design->name }}</p>

                        <h3 class="mt-1 text-sm font-bold sm:text-2xl">Esplora tutti i prodotti</h3>

                        <x-icon-arrow class="mt-3 size-4 transition-transform group-hover:translate-x-2 sm:mt-5 sm:size-5" />
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

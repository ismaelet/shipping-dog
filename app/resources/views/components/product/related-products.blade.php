@php
    $relatedProducts = $product->relatedProducts();
@endphp

<section class="bg-background-300 full-width border-border-400 border-b py-10 sm:py-14">
    @if ($relatedProducts->isNotEmpty())
        <div class="mb-6">
            <x-eyebrow>Il catalogo del creator</x-eyebrow>
            <x-heading variant="section-small">Altro di questo negozio</x-heading>
        </div>

        <div id="more-gall" class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($relatedProducts as $relatedProduct)
                <x-product-card :product="$relatedProduct" :display-badge="false" :light="false" />
            @endforeach
        </div>
    @endif
</section>

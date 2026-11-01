@use('Legacy\Models\Ecommerce\Product')
@use('Legacy\Models\Ecommerce\Product\ProductType')

@php
    $mainType = $product->type->shownType();
    $mainType->variants->prepend($mainType);
@endphp

<section>
    <x-product.options.label>Tipologia</x-product.options.label>

    <div class="product-options columns-[15rem]">
        @foreach ($mainType->variants as $variant)
            @php
                $relatedProduct = $variant->products->where('design_id', $product->design_id)->first();
            @endphp

            @if ($relatedProduct)
                <div class="product-option">
                    <input
                        type="radio"
                        name="variant_id"
                        class="peer hidden"
                        @checked($product->type->id == $variant->id)
                    />

                    <a href="{{ $relatedProduct->url() }}" class=""> {{ $variant->name }} </a>
                </div>
            @endif
        @endforeach
    </div>
</section>

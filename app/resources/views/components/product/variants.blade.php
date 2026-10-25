@use('Legacy\Models\Ecommerce\Product')
@use('Legacy\Models\Ecommerce\Product\ProductType')

@php
    $mainType = $product->type->shownType();
    $mainType->variants->prepend($mainType);
@endphp

<section id="gender" class="field relative !mt-6">
    <div class="">
        <x-label class="field-label !text-heading-400 !mb-3 !border-0 !p-0 !font-sans !text-sm !tracking-normal !normal-case">Tipologia</x-label>
    </div>

    <div class="flex flex-wrap gap-2">
        @foreach ($mainType->variants as $variant)
            @php
                $relatedProduct = $variant->products->where('design_id', $product->design_id)->first();
            @endphp

            @if ($relatedProduct)
                <div class="">
                    <input
                        type="radio"
                        name="variant_id"
                        class="peer hidden"
                        @checked($product->type->id == $variant->id)
                    />

                    <a
                        href="{{ $relatedProduct->url() }}"
                        class="gend_lbl !border-border-400 !text-heading-400 peer-checked:!border-primary-400 peer-checked:!text-primary-200 !min-w-0 !border"
                    >
                        <p class="gend_txt !px-3 !leading-8">{{ $variant->name }}</p>
                    </a>
                </div>
            @endif
        @endforeach
    </div>
</section>

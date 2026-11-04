<x-layouts.main :$title :$thumbnail :$location>
    <div class="product-page full-width">
        <input type="hidden" id="type-id" data-id="<?php echo $product->type_id; ?>" />

        <x-breadcrumbs :$breadcrumbs class="mt-8" />

        <x-product.data :$product :$productColors :$defaultColor :$defaultSize :$defaultPosition :$warning />

        <x-product.tags :$product />

        @if (! $product->isCustom() && $productsWithSameDesign->count())
            <x-product.other-types :$product :$productsWithSameDesign />
        @endif

        <x-product.related-products :$product />
    </div>
</x-layouts.main>

<x-layouts.main :$title :$thumbnail :$location>
    <div class="product-page full-width">
        <div class="product-page-content">
            <input type="hidden" id="type-id" data-id="<?php echo $product->type_id; ?>" />

            <x-breadcrumbs :$breadcrumbs class="mt-8" />

            <x-product.data :$product :$productColors :$defaultColor :$defaultSize :$defaultPosition :$warning />

            <x-product.tags :$product />

            <x-product.other-types :$product :$relatedGarments :$relatedAccessories />

            <x-product.related-products :$product />
        </div>
    </div>
</x-layouts.main>

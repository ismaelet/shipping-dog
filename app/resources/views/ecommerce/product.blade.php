@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/product.css') }}"
	>
	<style>
		main {
			--breakout-width: 1400px;
		}
	</style>
@endpushOnce

<x-layouts.legacy-main
	:$title
	:$thumbnail
	:$locationTag
>
	<input
		type="hidden"
		id="type-id"
		data-id="<?php echo $product->type_id; ?>"
	/>

	<x-product.data
		:$product
		:$productColors
	/>
	<x-product.tags :$product />
	<x-product.other-types
		:$product
		:$relatedGarments
		:$relatedAccessories
	/>
	<x-product.related-products :$product />
</x-layouts.legacy-main>

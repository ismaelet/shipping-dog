@pushOnce('styles')
	<style>
		main { --breakout-width: 1400px; }
	</style>
@endPushOnce

<x-layouts.main
	:$title
	:$thumbnail
	:$location
>
	<div class="full-width">
		<input
			type="hidden"
			id="type-id"
			data-id="<?php echo $product->type_id; ?>"
		/>

		<x-product.data
			:$product
			:$productColors
			:$defaultColor
			:$defaultSize
			:$defaultPosition
			:$warning
		/>

		<x-product.tags :$product />

		<x-product.other-types
			:$product
			:$relatedGarments
			:$relatedAccessories
		/>

		<x-product.related-products :$product />
	</div>
</x-layouts.main>

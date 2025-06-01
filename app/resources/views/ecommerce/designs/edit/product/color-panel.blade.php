@use('App\Models\Ecommerce\Product\ProductMockup')
@use('App\Models\Ecommerce\Product\ProductTypeMockupSize')

@php
	$function = 'saveColorChange';
	$productTypeMockup = ProductTypeMockupSize::firstByTypeId($product->type_id, $product->position_id);

	$productMockup = ProductMockup::where('product_id', $product->id)
	    ->where('position_id', $product->position_id)
	    ->first();
@endphp

@if ($productTypeMockup || $productMockup)
	@php

		$pX = $productMockup->pos_x ?? $productTypeMockup->pos_x_def;
		$pY = $productMockup->pos_y ?? $productTypeMockup->pos_y_def;
		$sX = $sY = $productMockup->size ?? $productTypeMockup->siz_x_def;

		$sX_max = $productTypeMockup->siz_x_max;
		$sY_max = $productTypeMockup->siz_y_max;
		$pX_min = $productTypeMockup->pos_x_min;
		$pY_min = $productTypeMockup->pos_y_min;

		$orientation = $product->design->ratio > $sX_max / $sY_max;

		// $clothingImagePosition = $position->base_of ?: $position->id;
		$clothing = $product->type->image($product->color_id, $product->position_id);
	@endphp

	@pushOnce('scripts')
		<script>
			function saveColorChange(button) {
				const colorPanel = button.closest('[data-panel]');
				const productId = button.closest('[data-product-id]').dataset.productId;
				const activeColors = colorPanel.querySelectorAll('[data-active-color]:checked');
				const defaultColor = colorPanel.querySelector('[data-default-color]:checked');
				const colors = Array.from(activeColors).map(activeColor => activeColor.value);
				const colorId = defaultColor.value ?? 1;
				const colorTag = defaultColor.dataset.defaultColorTag;

				return updateProduct(productId, colorId, colors, colorTag)
			}

			function updateProduct(productId, colorId, colors, colorTag) {
				return axios.patch(`/api/products/${productId}`, {
						color_id: colorId,
						colors: colors
					})
					.then(response => {
						refreshPreviewImage(productId)

						const productPanel = document.querySelector(`[data-product-id="${productId}"]`);
						const colothingImages = productPanel.querySelectorAll('.clothing, .clothing-details');

						colothingImages.forEach(clothingImage => {
							const positionId = clothingImage.dataset.position;
							const typeId = clothingImage.dataset.type;
							const suffix = clothingImage.dataset.suffix;

							updateProductType(typeId, colorId, positionId, suffix, clothingImage)
						});

						return true
					})
					.catch(error => {
						return false
					});
			}

			function updateProductType(typeId, colorId, positionId, suffix, element) {
				return axios.get(`/api/products/types/${typeId}/image`, {
						params: {
							color_id: colorId,
							position_id: positionId,
							suffix: suffix,
						}
					})
					.then(response => {
						const image = response.data.image;
						element.src = image;
					})
					.catch(error => {
						console.error(error)
					});
			}
		</script>
	@endpushOnce

	<section
		class="section<?= $key + 1 ?> section colors <?= $key == 0 ? 'active' : '' ?> grid grid-cols-1 lg:grid-cols-2"
		data-image-position="<?= $product->position->basicPositionId() ?>"
		data-panel
	>

		{{-- Preview image --}}
		@include('ecommerce.designs.edit.product.static-preview')

		<div class="data">

			<input
				type="hidden"
				name="product_id"
				value="<?= $product->id ?>"
			>

			<div class="item-colors-field colors-pan grid gap-8">
				<x-typography.component-heading class="text-center">Seleziona i colori disponibili</x-typography.component-heading>

				<p class="">Qui puoi selezionare i vari colori disponibili per questo capo e il colore principale,
					che verrà mostrato in tutte le anteprime.</p>

				<div class="colors-pan grid gap-8">

					{{-- Active colors --}}
					@include('ecommerce.designs.edit.product.active-colors')

					<!-- Default color -->
					@include('ecommerce.designs.edit.product.default-color')
				</div>

				@include('ecommerce.designs.edit.product.save-button')
			</div>
		</div>

	</section>
@endif

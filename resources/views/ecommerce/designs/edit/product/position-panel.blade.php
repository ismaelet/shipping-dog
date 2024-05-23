@use('App\Models\Ecommerce\Product\ProductMockup')
@use('App\Models\Ecommerce\Product\ProductTypeMockup')

@php
	$function = 'saveMockup';
	$key = $key + 1;
	$position = $tab[2];

	$enabled = $product->positions()->contains($position->id);

	$productTypeMockup = ProductTypeMockup::firstByTypeId($product->type_id, $position->id);
@endphp

@if ($productTypeMockup)
	@php
		$productMockup = ProductMockup::where('product_id', $product->id)
		    ->where('position_id', $position->id)
		    ->first();

		$pX = $productMockup->pos_x ?? $productTypeMockup->pos_x_def;
		$pY = $productMockup->pos_y ?? $productTypeMockup->pos_y_def;
		$sX = $sY = $productMockup->size ?? $productTypeMockup->siz_x_def;

		$sX_max = $productTypeMockup->siz_x_max;
		$sY_max = $productTypeMockup->siz_y_max;
		$pX_min = $productTypeMockup->pos_x_min;
		$pY_min = $productTypeMockup->pos_y_min;

		$orientation = $design->ratio > $sX_max / $sY_max;
	@endphp

	@pushOnce('scripts')
		<script>
			function saveMockup(button) {
				const positionPanel = button.closest('[data-panel]');
				const productPanel = button.closest('[data-product-id]')
				const productId = productPanel.dataset.productId;
				const positionId = positionPanel.dataset.imagePosition;
				const mockupId = positionPanel.dataset.mockupId;

				const size = positionPanel.querySelector('[name="preview_size"]').value;
				const posX = positionPanel.querySelector('[name="preview_position_x"]').value;
				const posY = positionPanel.querySelector('[name="preview_position_y"]').value;

				return response = mockupId ? updateMockup(mockupId, productId, positionId, size, posX, posY) : storeMockup(
					productId,
					positionId, size, posX, posY, positionPanel)
			}

			function storeMockup(productId, positionId, size, posX, posY, positionPanel) {
				return axios.post(`/api/products/mockups`, {
						product_id: productId,
						position_id: positionId,
						size: size,
						pos_x: posX,
						pos_y: posY,
					})
					.then(response => {
						mockupId = response.data.id
						positionPanel.dataset.mockupId = mockupId
						refreshPreviewImage(productId)
						return true
					})
					.catch(error => {
						console.error(error)
						return false
					});
			}

			function updateMockup(mockupId, productId, positionId, size, posX, posY) {
				return axios.patch(`/api/products/mockups/${mockupId}`, {
						size: size,
						pos_x: posX,
						pos_y: posY,
					})
					.then(response => {
						refreshPreviewImage(productId)
						return true
					})
					.catch(error => {
						console.error(error)
						return false
					});
			}
		</script>
	@endpushOnce

	<section
		class="section<?= $key ?> section positions <?= $key == 1 ? 'active' : '' ?> grid grid-cols-1 items-center lg:grid-cols-2"
		data-mockup-id="{{ $productMockup?->id }}"
		data-enabled="<?= $enabled ? 'true' : 'false' ?>"
		data-image-position="<?= $position->basicPositionId() ?>"
		data-panel
	>

		<input
			type="hidden"
			class="js-enabled"
			data-position="<?= $position->id ?>"
			data-enabled="<?= $enabled ? 1 : 0 ?>"
		>

		<!-- Preview image -->
		@include('ecommerce.designs.edit.product.preview')

		<input
			type="hidden"
			name="product_id"
			value="<?= $product->id ?>"
		>
		<input
			type="hidden"
			name="position_id"
			value="<?= $position->id ?>"
		>

		<div class="grid gap-8 py-10">
			<div class="grid gap-2">
				<x-typography.component-heading class="text-center">Stampa in posizione
					<?= strtolower($position->name) ?></x-typography.component-heading>

				<p><?= $position->info ?></p>

				<p>Puoi anche abilitare o disabilitare la possibilità di acquistare questa versione del prodotto usando il bottone
					qui sotto.</p>

				<p class="lg:hidden">Se non vedi tutte le impostazioni scorri verso il basso.</p>
			</div>


			<!-- Toggler button -->
			@include('ecommerce.designs.edit.product.toggler')

			<!-- Mockup management -->
			<div class="item-mockup-field grid gap-4">

				<!-- Size -->
				@include('ecommerce.designs.edit.product.position-range', [
					'name' => 'preview_size',
					'label' => 'Dimensione',
					'value' => $sX,
					'min' => 25,
					'max' => 100,
					'step' => 1,
				])

				<!-- Vertical position -->
				@include('ecommerce.designs.edit.product.position-range', [
					'name' => 'preview_position_y',
					'label' => 'Posizione verticale',
					'value' => $pY,
					'min' => 0,
					'max' => 100,
					'step' => 5,
				])

				<!-- Horizontal position -->
				@include('ecommerce.designs.edit.product.position-range', [
					'name' => 'preview_position_x',
					'label' => 'Posizione orizzontale',
					'value' => $pX,
					'min' => 0,
					'max' => 100,
					'step' => 5,
				])

			</div>

			@include('ecommerce.designs.edit.product.save-button')
		</div>
	</section>
@endif

@use('App\Models\Ecommerce\Product\ProductMockup')
@use('App\Models\Ecommerce\Product\ProductTypeMockup')

@php
	$productTypeMockup = ProductTypeMockup::firstByTypeId($product->type_id, $product->position_id);

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

	<section
		class="section<?= $key + 1 ?> section colors <?= $key == 0 ? 'active' : '' ?> grid grid-cols-1 lg:grid-cols-2"
		data-image-position="<?= $product->type->basicPositionId ?>"
	>

		<!-- Preview image -->
		@include('ecommerce.designs.edit.product.preview')

		<form
			method="POST"
			class="data"
		>

			<input
				type="hidden"
				name="product_id"
				value="<?= $product->id ?>"
			>

			<div class="item-colors-field colors-pan">
				<x-typography.component-heading>Seleziona i colori disponibili</x-typography.component-heading>

				<p class="">Qui puoi selezionare i vari colori disponibili per questo capo e il colore principale,
					che verrà mostrato in tutte le anteprime.</p>

				<div class="colors-pan mar-t-2">

					<!-- Available colors -->
					<p class="data-title inlb">Colori attivi</p>

					<x-help.info>È possibile comprare la tua grafica stampata su capi di questi colori. Attiva quelli che ti
						piacciono e disattiva quelli che non vuoi che siano disponibili per l'acquisto.</x-help.info>

					<div class="colorsA mar-b-2">

						<div class="inp-box color-grid">
							@php
								$colors = $product->type->colors->sortBy('hidden')->sortBy('preorder')->sortBy('reorder');
							@endphp

							@foreach ($colors as $color)
								@php
									$checked = $product->colors()->contains('id', $color->id);
								@endphp

								<label class="color-box">

									<input
										type="checkbox"
										class="cbx colorA-<?= $color->tag ?> hidden"
										name="colorA[]"
										value="<?= $color->tag ?>"
										data-color="<?= $color->tag ?>"
										<?= $checked ? 'checked' : '' ?>
									>

									<div
										class="color-icon"
										style="background-color: #<?= $color->color ?>;"
									></div>

								</label>
							@endforeach

						</div>
					</div>

					<!-- Default color -->
					<p class="data-title inlb">Colore predefinito</p>
					<x-help.info>Qui puoi selezionare il colore del capo che sarà mostrato come prima scelta in tutte le
						anteprime.</x-help.info>

					<div class="colorsD">

						<div class="inp-box color-grid">

							@php
								$colors = $product->type->colors->sortBy('hidden')->sortBy('preorder')->sortBy('reorder');
							@endphp

							@foreach ($colors as $color)
								@php
									$checked = $product->colors()->contains('id', $color->id);
								@endphp

								<label class="inlb color-box active">

									<input
										type="checkbox"
										class="cbx colorD-<?= $color->tag ?> hidden"
										name="colorD"
										value="<?= $color->id ?>"
										data-color="<?= $color->tag ?>"
										data-color-id="<?= $color->id ?>"
										data-dark="<?= $color->dark ? 1 : 0 ?>"
										<?= $checked ? 'checked' : '' ?>
									>

									<div
										class="color-icon"
										for="item-colorD-<?= $color->tag ?>"
										style="background-color: #<?= $color->hex ?>;"
									></div>
								</label>
							@endforeach

						</div>
					</div>
				</div>

				<div class="btn-box mar-t-2 text-center">
					<button
						type="submit"
						name="update-colors"
						class="pbtn"
					>Applica</button>
				</div>
			</div>
		</form>

	</section>
@endif

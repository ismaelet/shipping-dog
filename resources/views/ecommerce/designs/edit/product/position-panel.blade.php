@use('App\Models\Ecommerce\Product\ProductMockup')
@use('App\Models\Ecommerce\Product\ProductTypeMockup')

@php
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

		$orientation = $product->design->ratio > $sX_max / $sY_max;

		$clothing = $product->type->image($product->color_id, $position->imagePositionId);
	@endphp

	<section
		class="section<?= $key ?> section positions <?= $key == 1 ? 'active' : '' ?> <?= $enabled ? '' : 'disabled' ?>"
		data-enabled="<?= $enabled ? 1 : 0 ?>"
		data-image-position="<?= $position->imagePositionId ?>"
	>

		<input
			type="hidden"
			class="js-enabled"
			data-position="<?= $position->id ?>"
			data-enabled="<?= $enabled ? 1 : 0 ?>"
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
			<input
				type="hidden"
				name="position_id"
				value="<?= $position->id ?>"
			>

			<div>
				<h1 class="txt-15rem sec-title mar-b-1 brb2 br-c2 font-semibold">Stampa in posizione
					<?= strtolower($position->name) ?></h1>
				<p><?= $position->info ?></p>
				<p class="mob480 mar-b-2">Se non vedi tutte le impostazioni scorri verso il basso.</p>

				<!-- Toggler button -->
				<div class="toggler">

					<div class="toggler__button">
						<div class="line"></div>
						<div class="circle"></div>
					</div>

					<p class="off-text">Disabilitato</p>
					<p class="on-text">Abilitato</p>

				</div>

				<?php if (!$design->custom) { ?>
				<!-- Mockup management -->
				<div class="item-mockup-field mar-t-2 gap1 grid">

					<!-- Size -->
					<div class="">
						<p class="lbl mar-b-05">Dimensione:</p>

						<div class="mockup-inp inp grid">
							<input
								type="range"
								class="mockup-size wid-100 <?= $enabled ? '' : 'disabled' ?>"
								name="mockup-size"
								value="<?= $sX ?>"
								min="25"
								max="100"
								step="1"
								data-land="<?= $orientation ?>"
								<?= $enabled ? '' : 'disabled' ?>
							>
							<p class="txt-med txt-rig padr1"><?= $sX ?> %</p>
						</div>
					</div>

					<!-- Vertical position -->
					<div class="">
						<p class="lbl mar-b-05">Posizione verticale:</p>

						<div class="mockup-inp inp grid">
							<input
								type="range"
								class="mockup-y <?= $enabled ? '' : 'disabled' ?>"
								name="mockup-y"
								value="<?= $pY ?>"
								min="0"
								max="100"
								step="1"
								<?= $enabled ? '' : 'disabled' ?>
							>
							<p class="txt-med txt-rig padr1"><?= $pY ?> %</p>
						</div>
					</div>

					<!-- Horizontal position -->
					<div class="">
						<p class="lbl mar-b-05">Posizione orizzontale:</p>

						<div class="mockup-inp inp grid">
							<input
								type="range"
								class="mockup-x <?= $enabled ? '' : 'disabled' ?>"
								name="mockup-x"
								value="<?= $pX ?>"
								min="0"
								max="100"
								step="1"
								<?= $enabled ? '' : 'disabled' ?>
							>
							<p class="txt-med txt-rig padr1"><?= $pX ?> %</p>
						</div>
					</div>
				</div>

				<?php } ?>
			</div>

			<?php if (!$design->custom) { ?>
			<div class="btn-box mar-t-2 text-center">
				<button
					type="submit"
					name="update-position"
					class="pbtn"
				>Applica</button>
			</div>
			<?php } ?>

		</form>

	</section>
@endif

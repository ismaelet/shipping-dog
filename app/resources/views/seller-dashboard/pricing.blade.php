<?php

use App\Legacy\Ecommerce\Product\Gender;
use App\Legacy\Ecommerce\Product\Type as LegacyProductType;
use App\Legacy\System\DB;

?>

@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/pricing.css') }}"
	>
@endpushOnce

@pushOnce('scripts')
	<script defer>
		document.addEventListener('DOMContentLoaded', function() {
			function update_price(ele) {
				let parent = ele.closest('.price_box');

				let price = ele.value ? parseFloat(ele.value) : 0;
				let price_def = parent.querySelector('.price_def') ? parseFloat(parent.querySelector('.price_def')
					?.value) : 0;
				let gain_w_def = parent.querySelector('.gain_w_def') ? parseFloat(parent.querySelector('.gain_w_def')
					?.value) : 0;
				let gain_c_def = parent.querySelector('.gain_c_def') ? parseFloat(parent.querySelector('.gain_c_def')
					?.value) : 0;

				let base_w = price_def - gain_w_def / 0.75;
				let base_c = price_def - gain_c_def / 0.75;

				if (!price || price < 0) {
					price = 0.01;
				}

				let gain_w = (price - base_w) * 0.75;
				let gain_c = (price - base_c) * 0.75;

				if (gain_w < 0) gain_w = 0;
				if (gain_c < 0) gain_c = 0;

				gain_w = gain_w.toFixed(2);
				gain_c = gain_c.toFixed(2);

				let gain_w_input = parent.querySelector('.gain_w');
				if (gain_w_input) gain_w_input.value = gain_w + ' €';

				let gain_c_input = parent.querySelector('.gain_c');
				if (gain_c_input) gain_c_input.value = gain_c + ' €';
			}

			document.querySelectorAll('.price_inp').forEach(function(input) {
				input.addEventListener('input', function() {
					update_price(this);
				});

				input.addEventListener('blur', function() {

					let price = this.value ? parseFloat(this.value) : 0;
					let min = this.dataset.min ? parseFloat(this.dataset.min) : 0;

					if (price < min) {
						price = min;
					}

					this.value = price.toFixed(2);

					update_price(this);
				});
			});
		});
	</script>
@endpushOnce

<x-layouts.seller-dashboard>
	<div class="">
		<x-typography.component-heading>Modifica prezzi</x-typography.component-heading>

		<form
			class="form"
			method="POST"
		>
			@csrf

			<div class="pad-t-2 grid grid-cols-1 gap-8">

				<div class="dashboard-panel surface">

					@if (session('success'))
						<x-alerts.success class="mb-4">{{ session('success') }}</x-alerts.success>
					@endif

					<h1
						id="sub_cloth"
						class="subtitle"
					>Abbigliamento</h1>

					<div class="price_tbl">
						<div class="price_box">
							<div
								id="head_item"
								class="price_cell"
							>
								<p class="price_head txt-lef">Articolo</p>
							</div>
							<div
								id="head_price"
								class="price_cell"
							>
								<p class="price_head">Prezzo</p>
							</div>
							<div
								id="head_gain"
								class="price_cell"
							>
								<p class="price_head">Guadagno Bianchi</p>
							</div>
							<div
								id="head_gain"
								class="price_cell"
							>
								<p class="price_head">Guadagno colorati</p>
							</div>
						</div>

						<?php while ($row = $res_cloth->fetch_assoc()) {

				$type = new LegacyProductType($row['id']);
				$gender = new Gender($type->genderId);

				$row_price = DB::row('* FROM prices_stores WHERE seller_id = ? AND type_id = ?', [$seller->id, $type->id]) ?: DB::row('* FROM prices_default WHERE type_id = ?', [$type->id]);

				$price = $row_price['price'];
				$gain_w = $row_price['gain_w'];
				$gain_c = $row_price['gain_c'];
				$min = round($price - max($gain_w, $gain_c) / .75, 2);

			?>

						<div class="price_box">
							<input
								type="hidden"
								class="price_def"
								value="<?= $price ?>"
							>
							<input
								type="hidden"
								class="gain_w_def"
								value="<?= $gain_w ?>"
							>
							<input
								type="hidden"
								class="gain_c_def"
								value="<?= $gain_c ?>"
							>

							<p class="price_item"><?= $type->name . ' ' . $gender->name ?></p>

							<div class="price_cell">
								<input
									type="number"
									class="price price_inp price_edit"
									name="price_<?= $type->slug ?>"
									value="<?= format($price) ?>"
									min="<?= $min ?>"
									data-min="<?= $min ?>"
									step="0.01"
									placeholder="Prezzo"
									required
								>
							</div>
							<div class="price_cell"><input
									type="text"
									class="gain_w price_inp"
									value="<?= format($gain_w) ?> €"
									readonly
								></div>
							<div class="price_cell"><input
									type="text"
									class="gain_c price_inp"
									value="<?= format($gain_c) ?> €"
									readonly
								></div>
						</div>
						<?php } ?>
					</div>
				</div>

				<div class="dashboard-panel surface">
					<h1
						id="sub_misc"
						class="subtitle"
					>Accessori</h1>
					<div class="price_tbl">
						<div class="price_box">
							<div
								id="head_item"
								class="price_cell"
							>
								<p class="price_head txt-lef">Articolo</p>
							</div>
							<div
								id="head_price"
								class="price_cell"
							>
								<p class="price_head">Prezzo</p>
							</div>
							<div
								id="head_gain"
								class="price_cell"
							>
								<p class="price_head">Guadagno</p>
							</div>
						</div>

						<?php while ($row = $res_acc->fetch_assoc()) {

				$type = new LegacyProductType($row['id']);

				$row_price = DB::row('* FROM prices_stores WHERE seller_id = ? AND type_id = ?', [$seller->id, $type->id]) ?: DB::row('* FROM prices_default WHERE type_id = ?', [$type->id]);

				$price = $row_price['price'];
				$gain_w = $row_price['gain_w'];
				$gain_c = $row_price['gain_c'];
				$min = round($price - max($gain_w, $gain_c) / .75, 2);
			?>

						<div class="price_box">
							<input
								type="hidden"
								class="price_def"
								value="<?= $price ?>"
							>
							<input
								type="hidden"
								class="gain_w_def"
								value="<?= $gain_w ?>"
							>

							<p class="price_item"><?= $type->name ?></p>
							<div class="price_cell">
								<input
									type="number"
									class="price price_inp price_edit"
									name="price_<?= $type->slug ?>"
									value="<?= format($price) ?>"
									min="<?= $min ?>"
									data-min="<?= $min ?>"
									step="0.01"
									placeholder="Prezzo"
									required
								>
							</div>
							<div class="price_cell"><input
									type="text"
									class="gain_w price_inp"
									value="<?= format($gain_w) ?> €"
									readonly
								></div>
						</div>
						<?php } ?>
					</div>
				</div>

				<div class="flex justify-center">
					<x-button
						type="submit"
						name="submit"
						class=""
					>Aggiorna <span class="text-strong uppercase">tutti</span> gli articoli nel negozio</x-button>
				</div>
			</div>
		</form>
	</div>
</x-layouts.seller-dashboard>

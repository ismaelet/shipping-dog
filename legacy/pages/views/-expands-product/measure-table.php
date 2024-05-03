<?php

use App\Models\Ecommerce\Product\ProductTypeMeasure;

if ($size_table) { ?>

	<div id="size-lbox" class="lbox">

		<div class="lbox-bgr"></div>

		<div class="lbox-form-box">

			<div class="lbox-form">

				<div class="close-icon">
					<?= embed_icon('close') ?>
				</div>

				<h3 class="txt-15rem txt-semibold mar-b-1 brb2 br-blk">Taglie:</h3>
				<p class="">Se non vedi tutte le taglie scorri verso il basso.</p>

				<img src="<?= asset('/pages/assets/item/sizes/img.jpg') ?>" alt="" loading="lazy" class="size-table-img mar-t-1">

				<div class="size-table-grid mar-b-1">

					<div class="_empty"></div>

					<p class="font-semibold txt-line">Larghezza:</p>

					<p class="font-semibold txt-line">Altezza:</p>

					<?php foreach ($product->type->sizes as $size) {

						$measure = ProductTypeMeasure::where('type_id', $product->type_id)
							->where('size', $size->tag)
							->first();

						if ($measure) {
							$measureValues = explode(',', $measure->val);
					?>

							<p class="size-table-tag font-semibold"><?= strtoupper($size->name) ?>:</p>

							<p class=""><?= $measureValues[0] ?> cm</p>

							<p class=""><?= $measureValues[1] ?> cm</p>

					<?php }
					} ?>

				</div>

				<?php if (\Legacy\System\Classes\LegacyDB::exists('items_types_measures WHERE type_id=? AND size=?', [$product->type->id, 'mat'])) { ?>
					<p class="">Composizione: <?= \Legacy\System\Classes\LegacyDB::find('val FROM items_types_measures WHERE type_id=? AND size=?', [$product->type->id, 'mat']) ?></p>
				<?php } ?>

			</div>
		</div>
	</div>

<?php } ?>
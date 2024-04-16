<?php if ($size_table) { ?>

	<div id="size-lbox" class="lbox">

		<div class="lbox-bgr"></div>

		<div class="lbox-form-box">

			<div class="lbox-form">

				<div class="close-icon"></div>

				<p class="txt-15rem txt-bold mar-b-1 brb2 br-blk">Taglie:</p>
				<p class="">Se non vedi tutte le taglie scorri verso il basso.</p>

				<img src="<?= client_path('sizes/img.jpg') ?>" alt="" loading="lazy" class="size-table-img mar-t-1">

				<div class="size-table-grid mar-b-1">

					<div class="_empty"></div>

					<p class="txt-bold txt-line">Larghezza:</p>

					<p class="txt-bold txt-line">Altezza:</p>

					<?php foreach ($oldProduct->type->sizes as $size) {

						$measures = DBOld::find('val FROM items_types_measures WHERE type_id=? AND size=?', [$oldProduct->type->id, $size]);

					?>

						<p class="size-table-tag txt-bold"><?= strtoupper($size) ?>:</p>

						<p class=""><?= explode(',', $measures)[0] ?> cm</p>

						<p class=""><?= explode(',', $measures)[1] ?> cm</p>

					<?php } ?>

				</div>

				<?php if (DBOld::exists('items_types_measures WHERE type_id=? AND size=?', [$oldProduct->type->id, 'mat'])) { ?>
					<p class="">Composizione: <?= DBOld::find('val FROM items_types_measures WHERE type_id=? AND size=?', [$oldProduct->type->id, 'mat']) ?></p>
				<?php } ?>

			</div>
		</div>
	</div>

<?php } ?>
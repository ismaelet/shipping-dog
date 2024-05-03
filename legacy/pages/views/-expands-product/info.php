<?php

$desc = null;
$dett = null;

$res = \Legacy\System\Classes\LegacyDB::select('* FROM items_info WHERE product_id=' . $product->id);

if ($row = $res->fetch_assoc()) {
	$desc = $row['description'];
	$dett = str_replace(array('-', "\r\n"), array('&middot;', '<br>'), $row['details']);
} else if (!$product->isCustom()) {
	include_once('descriptions.php');
}

?>

<?php if ($desc || $dett || $product->coverImage()) { ?>

	<section id="info-sec" class="va-cen">

		<?php if ($desc || $dett) { ?>

			<div id="info-lef" class="info-cell half-wrap--left">
				<div id="info-head">
					<div>
						<label id="info-title-desc" class="info-title-box" data-pos="1">
							<input type="checkbox" id="info-cbx-desc" class="hidden info-cbx" checked>
							<h3 class="info-title">Dettagli</h3>
						</label>
						<label id="info-title-rev" class="info-title-box hidden" data-pos="2">
							<input type="checkbox" id="info-cbx-rev" class="hidden info-cbx">
							<h3 class="info-title">Recensioni</h3>
						</label>

						<div id="info-head-line" class="bgr-c1 no-trans"></div>
					</div>
				</div>

				<div id="info-body">

					<div id="info-lef-slide">

						<!-- Description -->
						<div class="info-slide">

							<h3 class="desc_stitle">Descrizione</h3>
							<p class="desc_txt"><?= $desc ?: '-' ?></p><br>

							<?php if (!$product->isCustom()) { ?>
								<h3 class="desc_stitle">Dettagli prodotto</h3>
								<p class="desc_txt"><?= $dett ?: '-' ?></p>
							<?php } ?>

						</div>
					</div>
				</div>
			</div>

		<?php } ?>

		<?php if ($product->coverImage()) { ?>

			<div id="info-rig" class="hei-100">

				<img src="<?= $product->coverImage() ?>" class="image-cover">
			</div>

		<?php } ?>

	</section>

<?php } ?>
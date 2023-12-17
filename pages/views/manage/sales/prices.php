<?php

use Ecommerce\Product\Gender;
use Ecommerce\Product\Type;

?>

{% block content %}

<h1 class="form-title">Modifica prezzi</h1>

<form class="form" method="POST">

	<div class="pad-t-2">

		<?php if ($done) { ?>
			<p class="confirmation">Prezzi modificati con successo</p>
		<?php } ?>

		<h1 id="sub_cloth" class="subtitle">Abbigliamento</h1>

		<div class="price_tbl">
			<div class="price_box">
				<div id="head_item" class="price_cell">
					<p class="price_head txt-lef">Articolo</p>
				</div>
				<div id="head_price" class="price_cell">
					<p class="price_head">Prezzo</p>
				</div>
				<div id="head_gain" class="price_cell">
					<p class="price_head">Guadagno Bianchi</p>
				</div>
				<div id="head_gain" class="price_cell">
					<p class="price_head">Guadagno colorati</p>
				</div>
			</div>

			<?php while ($row = $res_cloth->fetch_assoc()) {

				$type = new Type($row['ID']);
				$gender = new Gender($type->genderId);

				$row_price = DB::row('* FROM prices_stores WHERE storeID=? AND typeID=?', [$seller->id, $type->id]) ?: DB::row('* FROM prices_default WHERE typeID=?', [$type->id]);

				$price = $row_price['price'];
				$gain_w = $row_price['gain_w'];
				$gain_c = $row_price['gain_c'];
				$min = round($price - max($gain_w, $gain_c) / .75, 2);

			?>

				<div class="price_box">
					<input type="hidden" class="price_def" value="<?= $price; ?>">
					<input type="hidden" class="gain_w_def" value="<?= $gain_w; ?>">
					<input type="hidden" class="gain_c_def" value="<?= $gain_c; ?>">

					<p class="price_item"><?= $type->name . ' ' . $gender->name; ?></p>

					<div class="price_cell">
						<input type="number" class="price price_inp price_edit" name="price_<?= $type->slug; ?>" value="<?= format($price); ?>" min="<?= $min ?>" data-min="<?= $min ?>" step="0.01" placeholder="Prezzo" required>
					</div>
					<div class="price_cell"><input type="text" class="gain_w price_inp" value="<?= format($gain_w); ?> €" readonly></div>
					<div class="price_cell"><input type="text" class="gain_c price_inp" value="<?= format($gain_c); ?> €" readonly></div>
				</div>
			<?php } ?>
		</div>

		<h1 id="sub_misc" class="subtitle">Accessori</h1>
		<div class="price_tbl">
			<div class="price_box">
				<div id="head_item" class="price_cell">
					<p class="price_head txt-lef">Articolo</p>
				</div>
				<div id="head_price" class="price_cell">
					<p class="price_head">Prezzo</p>
				</div>
				<div id="head_gain" class="price_cell">
					<p class="price_head">Guadagno</p>
				</div>
			</div>

			<?php while ($row = $res_acc->fetch_assoc()) {

				$type = new Type($row['ID']);

				$row_price = DB::row('* FROM prices_stores WHERE storeID=? AND typeID=?', [$seller->id, $type->id]) ?: DB::row('* FROM prices_default WHERE typeID=?', [$type->id]);

				$price = $row_price['price'];
				$gain_w = $row_price['gain_w'];
				$gain_c = $row_price['gain_c'];
				$min = round($price - max($gain_w, $gain_c) / .75, 2);
			?>

				<div class="price_box">
					<input type="hidden" class="price_def" value="<?= $price; ?>">
					<input type="hidden" class="gain_w_def" value="<?= $gain_w; ?>">

					<p class="price_item"><?= $type->name; ?></p>
					<div class="price_cell">
						<input type="number" class="price price_inp price_edit" name="price_<?= $type->slug; ?>" value="<?= format($price); ?>" min="<?= $min ?>" data-min="<?= $min ?>" step="0.01" placeholder="Prezzo" required>
					</div>
					<div class="price_cell"><input type="text" class="gain_w price_inp" value="<?= format($gain_w); ?> €" readonly></div>
				</div>
			<?php } ?>
		</div>
	</div>

	<div class="form-foot">
		<button type="submit" name="submit" class="pbtn wid-100">Aggiorna <strong>tutti</strong> gli articoli nel negozio</button>
	</div>
</form>

{% end content %}
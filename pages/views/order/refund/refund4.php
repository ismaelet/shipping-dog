<?php

use Ecommerce\Order;
use Ecommerce\Product\Size;

?>

{% block form-body %}

<div id="item-cont" class="mar-b-1">

	<?php while ($row_sel = $res_sel->fetch_assoc()) {

		$ordID = $row_sel['ordID'];

		$order = new Order($ordID);

		$product = $order->product;

		if ($row_sel['type'] == 1) $size_new = (new Size($row_sel['size_new']))->name;

	?>

		<div class="item-box">

			<div class="item-tbl mar-v-1">

				<div class="row">

					<div class="item-cell item-pic_cell">
						<div class="data_row" method="POST">
							<div class="img_cont">
								<img src="<?= client_path($product->image) ?>" class="item-img img-fill">
							</div>
						</div>
					</div>

					<div class="item-cell item-name_cell">
						<h1 class="txt-1rem txt-bold"><?= $order->product->name ?></h1>
						<p class="item-name"><?= $order->fullDescription ?></p>
					</div>

					<div class="item-cell item-new_cell">

						<?php if ($row_sel['type'] == 1) { ?>
							<h3 class="txt-1rem txt-med">Nuova taglia:</h3>
							<p class="txt"><?= $size_new ?></p>
						<?php } else { ?>
							<p class="txt">RESO</p>
						<?php } ?>

					</div>
				</div>
			</div>
		</div>
	<?php } ?>
</div>

<p class="txt-1rem txt-bold"><strong>Istruzioni</strong>:</p>
<p class="txt info">
	<?= $instructions ?>
</p>

<p class="txt-1rem txt-bold mar-t-1"><strong>Note</strong>:</p>
<p class="txt info">
	<?= $notes ?>
</p>

<!-- <button type="submit" name="cancel" value="<?= $ordID ?>" class="txt remove pad-b-1">Cancella <?= $chan ? 'cambio' : 'reso' ?></button> -->

{% end form-body %}
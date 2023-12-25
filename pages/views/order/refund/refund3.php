<?php

use Ecommerce\Order;

?>

{% block form-body %}

<div id="item-cont">

	<?php while ($row = $res_sel->fetch_assoc()) {

		$order = new Order($row['ordID']);
		$product = $order->product ?>

		<div class="item-box">
			<div class="item-tbl">
				<div class="item-tbl">

					<div class="row">

						<div class="item-cell item-pic_cell">
							<div class="data_row" method="POST">

								<!--Image-->
								<div class="img_cont">
									<img src="<?= client_path($product->image) ?>" class="item-img img-fill">
								</div>
							</div>
						</div>

						<div class="item-cell item-name_cell">
							<h1 class="txt-1rem txt-bold"><?= $order->product->name ?></h1>
							<p class="item-name">
								<?= $order->fullDescription ?>
							</p>
						</div>

						<div class="item-cell item-new_cell">

							<?php if ($row['type'] == 1) { ?>

								<p class="lbl">Taglia desiderata</p>

								<select class="sel" name="size_new<?= $order->id ?>" required>

									<?php

									$sizes = rows_to_objects(DB::select('ID FROM items_sizes WHERE ID <> ? ORDER BY ID ASC', [$order->sizeId]), 'Ecommerce\Product\Size');

									foreach ($sizes as $key => $size) {
										if (in_array($size->slug, $product->type->sizes)) { ?>

											<option value="<?= $size->id ?>" <?= $row['size_new'] == $size->id ? 'selected' : '' ?>>
												<?= strtoupper($size->name) ?>
											</option>

									<?php }
									} ?>

								</select>

							<?php } else { ?>
								<p class="ref_title">RESO</p>
							<?php } ?>

							<button type="submit" name="remove" value="<?= $order->id ?>" class="txt remove">Cancella <?= $row['type'] == 1 ? 'cambio' : 'reso' ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
</div>

<p class="txt-12rem txt-bold mar-t-1"><strong>Istruzioni</strong>:</p>
<p class="txt info">
	<?= $instructions ?>
</p>

<p class="txt-12rem txt-bold mar-t-1"><strong>Note</strong>:</p>
<p class="txt info">
	<?= $notes ?>
</p>

<button type="submit" name="cancel" value="<?= $id ?>" class="txt-lef remove pad-b-1" formnovalidate>Cancella <?= $chan ? 'cambio' : 'reso' ?></button>

{% end form-body %}

{% block form-footer %}

<?php if ($chan) { ?>

	<button type="submit" name="pay" id="submit" class="button">Paga ritiro (<strong>8,00€</strong>)</button>

	<?php if (admin()) { ?>

		<button type="submit" name="free" class="button button--secondary" formnovalidate>Procedi senza pagare</button>

	<?php } ?>

<?php } else { ?>

	<button type="submit" name="refund" id="submit" class="button">Procedi</button>

<?php } ?>

{% end form-footer %}
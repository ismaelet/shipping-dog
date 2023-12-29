<?php

use App\Models\Ecommerce\Product\ProductSize;
use Ecommerce\Order;

?>

{% block form-body %}

<div id="item-cont">

	<?php foreach ($changes as $change) {
	?>

		<div class="item-box">
			<div class="item-tbl">
				<div class="item-tbl">
					<div class="row">
						<div class="item-cell item-pic_cell">
							<div class="data_row" method="POST">

								<!--Image-->
								<div class="img_cont">
									<img src="<?= $change->order->clientImage() ?>" class="item-img img-fill">
								</div>
							</div>
						</div>

						<div class="item-cell item-name_cell">
							<h1 class="txt-1rem txt-bold"><?= $change->order->product->name ?></h1>
							<p class="item-name">
								<?= $change->order->description() ?>
							</p>
						</div>

						<div class="item-cell item-new_cell">

							<?php if ($change->is_change) { ?>

								<p class="label">Taglia desiderata</p>

								<select class="" name="new_size_id[]" required>

									<?php $sizes = $change->order->product->type->sizeCollection();

									$sizes = $sizes->filter(function ($item) use ($change) {
										return $item->ID != $change->order->sizeID;
									});

									foreach ($sizes as $key => $size) { ?>

										<option value="<?= $size->ID ?>" <?= $change->new_size_id == $size->ID ? 'selected' : '' ?>>
											<?= strtoupper($size->name) ?>
										</option>

									<?php } ?>

								</select>

							<?php } else { ?>
								<p class="ref_title">RESO</p>
							<?php } ?>

							<button type="submit" name="remove" value="<?= $change->order->ID ?>" class="txt remove">Cancella <?= $change->is_change ? 'cambio' : 'reso' ?></button>
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

<button type="submit" name="cancel" value="<?= $changeRequestId ?>" class="txt-lef remove pad-b-1" formnovalidate>Cancella <?= $actualChanges ? 'cambio' : 'reso' ?></button>

{% end form-body %}

{% block form-footer %}

<?php if ($actualChanges) { ?>

	<button type="submit" name="pay" id="submit" class="button">Paga ritiro (<strong>8,00€</strong>)</button>

	<?php if (admin()) { ?>

		<button type="submit" name="free" class="button button--secondary" formnovalidate>Procedi senza pagare</button>

	<?php } ?>

<?php } else { ?>

	<button type="submit" name="refund" id="submit" class="button">Procedi</button>

<?php } ?>

{% end form-footer %}
<?php

?>

{% block form-body %}

<div id="item-cont">

	<?php foreach ($changes as $change) { ?>

		<div class="item-box">
			<div class="item-tbl">
				<div class="item-tbl">
					<div class="row">
						<div class="item-cell item-pic_cell">
							<div class="data_row" method="POST">

								<!--Image-->
								<div class="img_cont">
									<img src="<?= $change->old_order->clientImage() ?>" class="item-img img-fill">
								</div>
							</div>
						</div>

						<div class="item-cell item-name_cell">
							<h1 class="txt-1rem txt-bold"><?= $change->old_order->product->name ?></h1>
							<p class="item-name">
								<?= $change->old_order->description() ?>
							</p>
						</div>

						<div class="item-cell item-new_cell">

							<?php if ($change->is_change) { ?>

								<p class="label">Taglia desiderata</p>

								<select class="" name="<?= $change->id ?>_new_size_id" required>

									<?php $sizes = $change->old_order->product->type->sizeCollection();

									$sizes = $sizes->filter(function ($item) use ($change) {
										return $item->id != $change->old_order->sizeID;
									});

									foreach ($sizes as $key => $size) { ?>

										<option value="<?= $size->id ?>" <?= $change->new_size_id == $size->id ? 'selected' : '' ?>>
											<?= strtoupper($size->name) ?>
										</option>

									<?php } ?>

								</select>

							<?php } else { ?>
								<p class="ref_title">RESO</p>
							<?php } ?>

							<button type="submit" name="remove" value="<?= $change->id ?>" class="txt remove">Cancella <?= $change->is_change ? 'cambio' : 'reso' ?></button>
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

	<button type="submit" name="pay" id="submit" class="btn">Paga ritiro (<strong>8,00€</strong>)</button>

	<?php if (is_admin()) { ?>

		<button type="submit" name="skip_payment" class="btn btn--secondary" formnovalidate>Procedi senza pagare</button>

	<?php } ?>

<?php } else { ?>

	<button type="submit" name="refund" id="submit" class="btn">Procedi</button>

<?php } ?>

{% end form-footer %}
<?php

use Legacy\Classes\Ecommerce\Order;
use Legacy\Classes\Ecommerce\Product\Size;

?>

{% block form-body %}

<?php if ($changeRequest->payed) { ?>

	<div id="item-cont" class="mar-b-1">

		<?php foreach ($changeRequest->changes as $change) { ?>

			<div class="item-box">
				<div class="item-tbl mar-v-1">
					<div class="row">

						<div class="item-cell item-pic_cell">
							<div class="data_row" method="POST">
								<div class="img_cont">
									<img src="<?= $change->old_order->clientImage() ?>" class="item-img img-fill">
								</div>
							</div>
						</div>

						<div class="item-cell item-name_cell">
							<h1 class="txt-1rem txt-bold"><?= $change->old_order->product->name ?></h1>
							<p class="item-name"><?= $change->old_order->description() ?></p>
						</div>

						<div class="item-cell item-new_cell">

							<?php if ($change->is_change) { ?>

								<h3 class="txt-1rem txt-med">Nuova taglia:</h3>
								<p class="txt"><?= $change->new_size->name ?></p>

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

<?php } else { ?>

	<p class="text-center my-8">
		Il pagamento sta venendo processato. Per favore attendi...
	</p>

	<script>
		setTimeout(function() {
			window.location.href = '<?= client_url('/refund?step=4') ?>';
		}, 5000);
	</script>

<?php } ?>

{% end form-body %}
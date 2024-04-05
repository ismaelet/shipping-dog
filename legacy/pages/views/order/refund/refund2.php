<?php

?>

{% block form-body %}

<p class="label">Seleziona uno o più articoli da cambiare/restiturire:</p>

<?php if ($error == 'empty') { ?>
	<p class="error">Devi selezionare almeno un articolo</p>
<?php } ?>

<div class="order-list grid gap-4">

	<?php foreach ($orders as $order) { ?>

		<div class="item-row br-c1">
			<label class="grid pad-1 clickable">
				<input type="checkbox" class="hidden" name="order_id[]" value="<?= $order->ID ?>">

				<!-- Image -->
				<div class="item-img-box">
					<img src="<?= $order->clientImage() ?>" class="item-img img-fill">
				</div>

				<!-- Data -->
				<div class="pad-l-1">
					<h1 class="txt-bold txt-1rem mar-b-05"><?= $order->product->name ?></h1>

					<p class="text-small-paragraph">
						<?= $order->description() ?>
					</p>
				</div>
			</label>

			<div class="action-box pad-1">
				<p class="label">Azione richiesta:</p>
				<select name="is_change[]">

					<?php if (count($order->product->type->sizeList()) > 1) { ?>

						<option value="1">Cambio</option>

					<?php } ?>

					<option value="0">Rimborso</option>
				</select>
			</div>
		</div>

	<?php } ?>

</div>

{% end form-body %}

{% block form-footer %}

<button type="submit" name="submit" id="submit" class="button">Richiedi</button>
<button type="submit" name="cancel" class="remove mar-t-1 txt-lef">Cancella cambio</button>

{% end form-footer %}
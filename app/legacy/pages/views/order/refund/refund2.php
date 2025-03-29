<?php

?>

{% block form-body %}

<p class="label">Seleziona uno o più articoli da cambiare/restiturire:</p>

<?php if ($error == 'empty') { ?>
	<p class="error">Devi selezionare almeno un articolo</p>
<?php } ?>

<div class="order-list grid gap-4">

	<?php foreach ($order_items as $orderItem) { ?>

		<div class="item-row br-c1">
			<label class="grid pad-1 clickable">
				<input type="checkbox" class="hidden" name="order_id[]" value="<?= $orderItem->id ?>">

				<!-- Image -->
				<div class="item-img-box">
					<img src="<?= $orderItem->image() ?>" class="item-img img-fill">
				</div>

				<!-- Data -->
				<div class="pad-l-1">
					<h1 class="font-semibold  mar-b-05"><?= $orderItem->product->name ?></h1>

					<p class="text-small-paragraph">
						<?= $orderItem->description() ?>
					</p>
				</div>
			</label>

			<div class="action-box pad-1">
				<p class="label">Azione richiesta:</p>
				<select name="is_change[]">

					<?php if ($orderItem->product->type->sizes->count() > 1) { ?>

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

<button type="submit" name="submit" id="submit" class="btn">Richiedi</button>
<button type="submit" name="cancel" class="remove mar-t-1 txt-lef">Cancella cambio</button>

{% end form-footer %}
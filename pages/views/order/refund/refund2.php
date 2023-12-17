<?php

use Ecommerce\Order;

?>

{% block form-body %}

<p class="lbl">Seleziona uno o più articoli da cambiare/restiturire:</p>

<?php if ($error == 'empty') { ?>
	<p class="error">Devi selezionare almeno un articolo</p>
<?php } ?>

<?php while ($row_ord = $res_sel->fetch_assoc()) {

	$order = new Order($row_ord['ID']);
	$product = $order->product ?>

	<div class="item-row mar-v-1 br-c1">

		<div class="grid pad-1 clickable">

			<input type="checkbox" class="hidden cbx" name="ordID[]" value="<?= $order->id ?>">

			<!-- Image -->
			<div class="item-img-box">
				<img src="<?= client_path($order->product->image) ?>" class="item-img img-fill">
			</div>

			<!-- Data -->
			<div class="pad-l-1">
				<h1 class="txt-bold txt-1rem mar-b-05"><?= $order->product->name ?></h1>

				<p class="">
					<?= $order->fullDescription ?>
				</p>
			</div>
		</div>

		<div class="action-box pad-1 hidden">
			<p class="lbl mar-b-1">Azione richiesta:</p>
			<select class="" name="type<?= $order->id ?>">

				<?php
				if (count($product->type->sizes) > 1) { ?>
					<option value="1">Cambio</option>
				<?php } ?>

				<option value="2">Rimborso</option>
			</select>
		</div>
	</div>
<?php } ?>

{% end form-body %}

{% block form-footer %}

<button type="submit" name="submit" id="submit" class="button">Richiedi</button>
<button type="submit" name="cancel" class="remove mar-t-1 txt-lef">Cancella cambio</button>

{% end form-footer %}
<?php

use Ecommerce\Design;
use Ecommerce\Order;

use function Ecommerce\format_price;

?>

<!--Designs version-->
<div class="list-tbl mar-b-4">

	<?php
	$row_dark = true;
	$designs = DBOld::select('SUM((' . Order::PRODUCT_GAIN_FIELD . ' - gain_disc) * quantity) as totGain, COUNT(*) as count, design_id FROM orders WHERE product_id IN (SELECT id FROM items WHERE user_id = ?) AND cart_id IN (SELECT id FROM carts WHERE status>0) AND design_id IN (SELECT id FROM designs WHERE status = 1) GROUP BY design_id ORDER BY totGain DESC', [auth()->id()]);
	?>

	<div class="list-tbl-head">
		<p class="list-name shr">Quantità</p>
		<p class="list-name lon">Design</p>
		<p class="list-name shr">Guadagno</p>
		<p class="list-name shr">Ultima vendita</p>
		<p class="list-name shr">Aggiunto il</p>
	</div>

	<?php while ($designData = $designs->fetch_assoc()) {

		$design = new Design($designData['design_id']);

		//get last sale
		$lastOrderId = DBOld::find('id FROM orders WHERE design_id=? AND cart_id IN (SELECT id FROM carts WHERE status>0) ORDER BY time DESC', [$design->id]);

		$lastOrder = new Order($lastOrderId);
	?>

		<!--sale line-->
		<div class="list-row <?php if ($row_dark) {
									echo 'row_dark';
									$row_dark = false;
								} else $row_dark = 'true'; ?>">
			<div class="list-cell"><input type="text" class="list-inp" value="<?= $designData['count'] ?>" disabled></div>
			<div class="list-cell"><input type="text" class="list-inp" value="<?= $design->name ?>" disabled></div>
			<div class="list-cell"><input type="text" class="list-inp" value="<?= format_price($designData['totGain']) ?>" disabled></div>
			<div class="list-cell"><input type="text" class="list-inp" value="<?= $lastOrder->timeString ?>" disabled></div>
			<div class="list-cell"><input type="text" class="list-inp" value="<?= $design->time ?>" disabled></div>
		</div>

	<?php } ?>

</div>
<?php

use App\Models\Ecommerce\Order as EcommerceOrder;
use Legacy\Classes\Ecommerce\Order;

if (!$year) $year = \Legacy\System\Classes\LegacyDB::row('YEAR(time) as year FROM orders WHERE product_id IN (SELECT id FROM items WHERE user_id = ?) AND cart_id IN (SELECT id FROM carts WHERE status>0) ORDER BY time DESC', [$oldSeller->id])['year'];

$res_months = \Legacy\System\Classes\LegacyDB::select('MONTH(time) as month FROM orders WHERE product_id IN (SELECT id FROM items WHERE user_id = ?) AND cart_id IN (SELECT id FROM carts WHERE status>0) AND YEAR(time)=? GROUP BY MONTH(time) ORDER BY MONTH(time) DESC', [$oldSeller->id, $year]);

while ($row_months = $res_months->fetch_assoc()) {

	$row_dark = true;

	$month = $row_months['month'];
	$month_str = ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];

	$row_month = \Legacy\System\Classes\LegacyDB::row(
		'
		SUM((' . Order::PRODUCT_GAIN_FIELD . ' - gain_disc) * orders.quantity) as totGain, COUNT(*) as count
		FROM orders
		LEFT JOIN carts ON orders.cart_id = carts.id
		WHERE
			YEAR(orders.time) = ? AND
			MONTH(orders.time) = ? AND
			seller_id = ? AND
			carts.status > 0',
		[$year, $month, $oldSeller->id]
	);

	$month_tot = $row_month['totGain'];
	$month_num = $row_month['count'];

	$orders = EcommerceOrder::where('seller_id', $oldSeller->id)
		->where('status', '>', 0)
		->whereYear('time', $year)
		->whereMonth('time', $month)
		->orderByDesc('time')
		->get();
?>

	<div class="sales month-box pad-v-2 mar-b-1">

		<h3 class="month-title inlb br-c1"><span class="txt-med"><?= $month_str[$month - 1] ?></span> <span class="title-year"><?= $year ?></span></h3>

		<p class="month-gain inlb"><strong><?= format($month_tot) ?> €</strong> da <strong><?= $month_num ?></strong> vendite</p>

		<button type="button" class="sbtn month-btn clickable" onclick="$(this).next().toggleClass('hidden'); $(this).hide();"><span class="month-btn-text">Mostra le vendite</span></button>

		<div class="tbl-box pad-t-2 hidden">
			<div class="sale-history__grid grid">

				<div class="grid-row sale-history__head">
					<p class="grid-item">Design</p>
					<p class="grid-item">Guadagno</p>
					<p class="grid-item">Capo</p>
					<p class="grid-item">Colore</p>
					<p class="grid-item">Sconto</p>
					<p class="grid-item">Data</p>
				</div>

				<?php foreach ($orders as $order) { ?>

					<!--sale line-->
					<div class="grid-row <?php if ($row_dark) {
												echo 'row_dark';
												$row_dark = false;
											} else $row_dark = 'true' ?>">
						<div class="grid-item">
							<p class="list-inp">
								<?php
								if ($order->quantity > 1) echo $order->quantity . " x ";
								echo $order->product ? $order->product->name : '- design rimosso -';
								?>
							</p>
						</div>
						<div class="grid-item">
							<p class="list-inp"><?= $order->totalGainString() ?></p>
						</div>
						<div class="grid-item">
							<p class="list-inp"><?= $order->product->type->name ?? '' ?></p>
						</div>
						<div class="grid-item">
							<p class="list-inp"><?= $order->color->name ?? '' ?></p>
						</div>
						<div class="grid-item">
							<p class="list-inp"><?= $order->cart->code ?: '-' ?></p>
						</div>
						<div class="grid-item">
							<p class="list-inp"><?= $order->timeString() ?></p>
						</div>
					</div>

				<?php } ?>

			</div>
		</div>
	</div>

<?php } ?>

<?php

$res_year = \Legacy\System\Classes\LegacyDB::select('YEAR(time) as year FROM orders WHERE product_id IN (SELECT id FROM items WHERE user_id = ?) AND cart_id IN (SELECT id FROM carts WHERE status>0) GROUP BY YEAR(time) ORDER BY YEAR(time) DESC', [$oldSeller->id]);

if ($res_year->num_rows > 1) { ?>

	<div id="years-box" class="txt-cen pad-t-2 pad-b-5 gallery">

		<?php while ($row = $res_year->fetch_assoc()) {

			$yearM = $row['year'] ?>

			<label>

				<input type="checkbox" class="hidden" value="<?= $yearM ?>" onchange="$('#year-inp').val($(this).val()); $('#search-btn').click();">

				<p class="year clickable inlb bgr-c1-h txt-wht-h <?php if ($yearM == $year) echo 'active' ?>">
					<?= $yearM ?>
				</p>

			</label>

		<?php } ?>

	</div>

<?php } ?>
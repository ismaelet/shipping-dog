<?php

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\OrderItem;

$sales = OrderItem::where('seller_id', $seller->id)
	->where('status', '>=', EcommerceHelper::statusFromTag('confirmed'))
	->orderByDesc('created_at')
	->select('*')
	->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
	->with('product', 'product.type', 'color', 'order')
	->get();

if (!$currentYear) {
	$currentYear = $sales->first()?->year;
}

$firstYear = $sales->last()?->year;
$lastYear = $sales->first()?->year;

$firstMonth = $sales->where('year', $currentYear)->last()?->month;
$lastMonth = $sales->where('year', $currentYear)->first()?->month;

for ($month = $lastMonth; $month >= $firstMonth; $month--) {

	$row_dark = true;

	$month_str = ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];

	$monthTotGain = $sales->sum(function ($orderItem) use ($currentYear, $month) {
		if ($orderItem->year == $currentYear && $orderItem->month == $month) {
			return $orderItem->gain;
		}
	});

	$orderItems = $sales->where('year', $currentYear)->where('month', $month);

	$monthSalesCount = $orderItems->count();
?>

	<div class="sales month-box pad-v-2 mar-b-1">

		<h3 class="month-title inlb br-c1"><span class="txt-med"><?= $month_str[$month - 1] ?></span> <span class="title-year"><?= $currentYear ?></span></h3>

		<p class="month-gain inlb"><strong><?= format($monthTotGain) ?> €</strong> da <strong><?= $monthSalesCount ?></strong> vendite</p>

		<button type="button" class="sbtn month-btn clickable" onclick="$(this).next().toggleClass('hidden'); $(this).hide();"><span class="month-btn-text">Mostra le vendite</span></button>

		<div class="tbl-box pad-t-2 hidden">
			<div class="sale-history__grid grid">

				<div class="grid-row sale-history__head">
					<p class="grid-item">ID Ordine</p>
					<p class="grid-item">Design</p>
					<p class="grid-item">Guadagno</p>
					<p class="grid-item">Capo</p>
					<p class="grid-item">Colore</p>
					<p class="grid-item">Sconto</p>
					<p class="grid-item">Data</p>
				</div>

				<?php foreach ($orderItems as $orderItem) { ?>

					<!--sale line-->
					<div class="grid-row">
						<div class="grid-item">
							<p class="list-inp"><?= $orderItem->idCode() ?></p>
						</div>
						<div class="grid-item">
							<p class="list-inp">
								<?php
								if ($orderItem->quantity > 1) echo $orderItem->quantity . " x ";
								echo $orderItem->product ? $orderItem->product->name : '- design rimosso -';
								?>
							</p>
						</div>
						<div class="grid-item">
							<p class="list-inp"><?= $orderItem->gainString() ?></p>
						</div>
						<div class="grid-item">
							<p class="list-inp"><?= $orderItem->product->type->name ?? '' ?></p>
						</div>
						<div class="grid-item">
							<p class="list-inp"><?= $orderItem->color->name ?? '' ?></p>
						</div>
						<div class="grid-item">
							<p class="list-inp"><?= $orderItem->order?->code ?: '-' ?></p>
						</div>
						<div class="grid-item">
							<p class="list-inp"><?= $orderItem->createdAtString() ?></p>
						</div>
					</div>

				<?php } ?>

			</div>
		</div>
	</div>

<?php } ?>

<?php

$res_year = \Legacy\System\Classes\LegacyDB::select('YEAR(created_at) as year FROM order_items WHERE product_id IN (SELECT id FROM products WHERE user_id = ?) AND order_id IN (SELECT id FROM orders WHERE status>0) GROUP BY YEAR(created_at) ORDER BY YEAR(created_at) DESC', [$seller->id]);

if ($res_year->num_rows > 1) { ?>

	<div id="years-box" class="text-center pad-t-2 pad-b-5 gallery">

		<?php while ($row = $res_year->fetch_assoc()) {

			$currentYearM = $row['year'] ?>

			<label>

				<input type="checkbox" class="hidden" value="<?= $currentYearM ?>" onchange="$('#year-inp').val($(this).val()); $('#search-btn').click();">

				<p class="year clickable inlb bgr-c1-h txt-wht-h <?php if ($currentYearM == $currentYear) echo 'active' ?>">
					<?= $currentYearM ?>
				</p>

			</label>

		<?php } ?>

	</div>

<?php } ?>
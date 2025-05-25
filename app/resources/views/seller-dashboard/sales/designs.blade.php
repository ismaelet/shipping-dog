<?php

use App\Models\Ecommerce\OrderItem;

?>

<!--Designs version-->
<div class="list-tbl mt-8">

	<?php
	$row_dark = true;
	
	$order_items = OrderItem::join('designs', 'order_items.design_id', '=', 'designs.id')
	    ->where('order_items.seller_id', auth()->id())
	    ->where('order_items.status', '>', 0)
	    ->where('designs.status', 1)
	    ->selectRaw('SUM((productGain - gain_disc) * quantity) as totGain, COUNT(*) as count, designs.name, designs.id, designs.time')
	    ->groupBy(['designs.id', 'designs.name', 'designs.time'])
	    ->orderByDesc('totGain')
	    ->get();
	?>

	<div class="list-tbl-head">
		<p class="list-name shr">Quantità</p>
		<p class="list-name lon">Design</p>
		<p class="list-name shr">Guadagno</p>
		<p class="list-name shr">Ultima vendita</p>
		<p class="list-name shr">Aggiunto il</p>
	</div>

	<?php foreach ($order_items as $designData) {

		//get last sale
		$lastOrder = OrderItem::where('design_id', $designData->id)
			->where('status', '>', 0)
			->orderByDesc('time')
			->first();
	?>

	<!--sale line-->
	<div class="list-row <?php if ($row_dark) {
	    echo 'row_dark';
	    $row_dark = false;
	} else {
	    $row_dark = 'true';
	} ?>">
		<div class="list-cell">
			<p class="list-inp text-sm"><?= $designData->count ?></p>
		</div>
		<div class="list-cell">
			<p class="list-inp text-sm"><?= $designData->name ?></p>
		</div>
		<div class="list-cell">
			<p class="list-inp text-sm"><?= format_price($designData->totGain) ?></p>
		</div>
		<div class="list-cell">
			<p class="list-inp text-sm"><?= $lastOrder->createdAtString() ?></p>
		</div>
		<div class="list-cell">
			<p class="list-inp text-sm"><?= $designData->createdAtString() ?></p>
		</div>
	</div>

	<?php } ?>

</div>

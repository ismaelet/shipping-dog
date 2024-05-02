<?php

use Legacy\View\Components\Form\SearchInput;

?>

{% block main %}

<div class="p-4 grid gap-8">
	<div>
		<label class="label">Parti dall'ordine:</label>

		<div class="flex gap-4">
			<form action="" method="POST" class="w-full">
				<?= SearchInput::render([
					'search' => 'orders',
					'storedName' => 'start_order_id',
					'storedValue' => $startFromCart ? $startFromCart->id : null,
					'shownValue' => $startFromCart ? $startFromCart->id . ' - ' . $startFromCart->address->full_name : '',
					'placeholder' => 'Ordine da cui partire',
					'required' => true,
				]) ?>
			</form>

			<form action="" method="POST">
				<button type="submit" class="btn btn--secondary" name="clean_log"><?= embed_icon('clean') ?>Pulisci log</button>
			</form>
		</div>
	</div>

	<div class="btn js-copy-order_items"><?= embed_icon('copy') ?>Copia tutto</div>
</div>

<p class="log">

	/// <?= $validorders->count() ? $filteredorder_items->count() : 0 ?> ARTICOLI DA FARE<br>

</p>

<div class="order_items">

	<?php if ($validorders->count()) { ?>

		<p class="log">
			--------------------------- <span class="text-strong">DESIGN</span> ---------------------------
		</p>

		<p class="log">
			<?php

			$sortedBySellers = $filteredorder_items
				->sortBy('seller_id')
				->unique('seller_id');

			foreach ($sortedBySellers as $sellerOrder) {

				// Seller name
				echo '<span style="color: #ff9600;">[ ' . strtoupper($sellerOrder->storeName) . ' ]</span><br>';

				// Get the order_items
				$sortedByProducts = $filteredorder_items
					->where('seller_id', '=', $sellerOrder->seller_id)
					->sortBy('design_id')
					->unique('design_id');

				// Designs
				foreach ($sortedByProducts as $product) {
					$mergedorder_items = [];

					// Product name (grouped by design_id)
					echo '<span class="text-color-highlighted">' . ($product->design_id ?  $product->design_id . ' - ' : '') . $product->productName . '</span><br>';

					// Get the order_items
					$order_items = $filteredorder_items
						->where('seller_id', '=', $product->user_id)
						->where('design_id', '=', $product->design_id)
						->sortBy('size_id')
						->sortBy('color_id')
						->sortBy('type_id');

					// Merge order_items
					foreach ($order_items as $orderItem) {
						$orderItemDescription = $orderItem->description();
						$orderItemDescription = $orderItem->inventory ? $orderItemDescription . ' <strong>(INVENTARIO)</strong>' : $orderItemDescription;

						if (isset($mergedorder_items[$orderItemDescription])) {
							$mergedorder_items[$orderItemDescription] += $orderItem->quantity;
						} else {
							$mergedorder_items[$orderItemDescription] = 1;
						}
					}

					// Print order_items
					foreach ($mergedorder_items as $orderItemDescription => $quantity) {
						echo $quantity . ' x ' . $orderItemDescription . '<br>';
					}

					echo '<br>';
				}

				echo '<br>';
			} ?>

		</p>


		<p class="log">
			--------------------------- <span class="text-strong">PRODOTTI</span> ---------------------------
		</p>

		<p class="log">

			<?php
			$sortedByTypes = $filteredorder_items
				->sortBy('type_id')
				->unique('type_id');

			foreach ($sortedByTypes as $typeOrder) {

				$mergedorder_items = [];

				// Seller name
				echo '<span style="color: #ff9600;">[ ' . strtoupper($typeOrder->productTypeName) . ' ]</span><br>';

				// Get the order_items
				$sortedByData = $filteredorder_items
					->where('inventory', 0)
					->where('type_id', '=', $typeOrder->type_id)
					->sortBy('color_id')
					->sortBy('size_id');

				// Merge order_items
				foreach ($sortedByData as $orderItem) {
					$orderItemDescription = [];
					$orderItemDescription[] = $orderItem->size && strpos($orderItem->product->type->sizes, ',') ? $orderItem->size->name : null;
					$orderItemDescription[] = $orderItem->color && strpos($orderItem->product->type->colors, ',') ? $orderItem->color->name : null;
					$orderItemDescription = implode(' ', array_filter($orderItemDescription)) ?: 'Prodotto standard';

					if (isset($mergedorder_items[$orderItemDescription])) {
						$mergedorder_items[$orderItemDescription] += $orderItem->quantity;
					} else {
						$mergedorder_items[$orderItemDescription] = 1;
					}
				}

				// Print order_items
				foreach ($mergedorder_items as $orderItemDescription => $quantity) {
					echo $quantity . ' x ' . $orderItemDescription . '<br>';
				}

				echo '<br>';
			} ?>

		</p>

	<?php } ?>

</div>

{% end main %}
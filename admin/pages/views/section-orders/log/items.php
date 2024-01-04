<?php

?>

{% block main %}

<div class="p-4">
	<label class="label">Parti dall'ordine:</label>

	<div class="flex gap-4">
		<form action="" method="POST" class="w-full">
			<input type="number" name="product_log_start_from_id" value="<?= $validCarts->count() ? $firstCart->cartId : $startFromId ?>" placeholder="Parti del numero ordine #">
		</form>

		<form action="" method="POST">
			<button type="submit" class="button" name="clean-log">Pulisci log</button>
		</form>
	</div>
</div>

<p class="log">

	/// <?= $validCarts->count() ? $filteredOrders->count() : 0 ?> ARTICOLI DA FARE<br>


	<?php if ($validCarts->count()) { ?>
		/// A PARTIRE DA ORDINE <span class="fw-bold">#<?= $firstCart->cartId ?></span> (<span class="fw-bold"><?= $firstCart->customerName ?></span>)
	<?php } ?>

</p>


<?php if ($validCarts->count()) { ?>

	<p class="log">
		--------------------------- <span style="color: #fff;">DESIGN</span> ---------------------------
	</p>

	<p class="log">
		<?php

		$sortedBySellers = $filteredOrders
			->sortBy('sellerId')
			->unique('sellerId');

		foreach ($sortedBySellers as $sellerOrder) {

			// Seller name
			echo '<span style="color: #ff9600;">[ ' . strtoupper($sellerOrder->storeName) . ' ]</span><br>';

			// Get the orders
			$sortedByProducts = $filteredOrders
				->where('sellerId', '=', $sellerOrder->sellerId)
				->sortBy('designId')
				->unique('designId');

			// Designs
			foreach ($sortedByProducts as $product) {
				$mergedOrders = [];

				// Product name (grouped by designId)
				echo '<span style="color: #fff;">' . ($product->designId ?  $product->designId . ' - ' : '') . $product->productName . '</span><br>';

				// Get the orders
				$orders = $filteredOrders
					->where('sellerId', '=', $product->sellerId)
					->where('designId', '=', $product->designId)
					->sortBy('sizeID')
					->sortBy('colorID')
					->sortBy('typeID');

				// Merge orders
				foreach ($orders as $order) {
					$orderDescription = $order->description();

					if (isset($mergedOrders[$orderDescription])) {
						$mergedOrders[$orderDescription] += $order->quantity;
					} else {
						$mergedOrders[$orderDescription] = 1;
					}
				}

				// Print orders
				foreach ($mergedOrders as $orderDescription => $quantity) {
					echo $quantity . ' x ' . $orderDescription . '<br>';
				}

				echo '<br>';
			}

			echo '<br>';
		} ?>

	</p>


	<p class="log">
		--------------------------- <span style="color: #fff;">PRODOTTI</span> ---------------------------
	</p>

	<p class="log">

		<?php
		$sortedByTypes = $filteredOrders
			->sortBy('typeID')
			->unique('typeID');

		foreach ($sortedByTypes as $typeOrder) {

			$mergedOrders = [];

			// Seller name
			echo '<span style="color: #ff9600;">[ ' . strtoupper($typeOrder->productTypeName) . ' ]</span><br>';

			// Get the orders
			$sortedByData = $filteredOrders
				->where('typeID', '=', $typeOrder->typeID)
				->sortBy('colorID')
				->sortBy('sizeID');

			// Merge orders
			foreach ($sortedByData as $order) {
				$orderDescription = [];
				$orderDescription[] = $order->size && strpos($order->product->type->sizes, ',') ? $order->size->name : null;
				$orderDescription[] = $order->color && strpos($order->product->type->colors, ',') ? $order->color->name_it : null;
				$orderDescription = implode(' ', array_filter($orderDescription)) ?: 'Prodotto standard';

				if (isset($mergedOrders[$orderDescription])) {
					$mergedOrders[$orderDescription] += $order->quantity;
				} else {
					$mergedOrders[$orderDescription] = 1;
				}
			}

			// Print orders
			foreach ($mergedOrders as $orderDescription => $quantity) {
				echo $quantity . ' x ' . $orderDescription . '<br>';
			}

			echo '<br>';
		} ?>

	</p>

<?php } ?>

{% end main %}
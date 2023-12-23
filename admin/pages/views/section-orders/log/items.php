<?php

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Order;

?>

{% block main %}

<p class="log">

	/// <?= $filteredOrders->count() ?> ARTICOLI DA FARE<br>

	<?php if ($filteredOrders->count()) {
		$firstCart = $filteredOrders->sortBy('cartID')->first();
	?>

		/// A PARTIRE DA ORDINE <span class="fw-bold">#<?= $firstCart->cartId ?></span> (<span class="fw-bold"><?= $firstCart->customerName ?></span>)

</p>

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
		}
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

<form method="POST" class="flex-group center-content mar-t-2">
	<button type="submit" name="submit" class="button" onclick="return confirm('Sei sicuro di voler pulire il log?')">Pulisci log</button>
</form>

{% end main %}
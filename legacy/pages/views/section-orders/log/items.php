<?php

use App\View\Components\Form\SearchInput;

?>

{% block main %}

<div class="p-4 grid gap-8">
	<div>
		<label class="label">Parti dall'ordine:</label>

		<div class="flex gap-4">
			<form action="" method="POST" class="w-full">
				<?= SearchInput::render([
					'search' => 'carts',
					'storedName' => 'start_cart_id',
					'storedValue' => $startFromCart ? $startFromCart->ID : null,
					'shownValue' => $startFromCart ? $startFromCart->ID . ' - ' . $startFromCart->address->aname : '',
					'placeholder' => 'Ordine da cui partire',
					'required' => true,
				]) ?>
			</form>

			<form action="" method="POST">
				<button type="submit" class="btn btn--secondary" name="clean_log"><?= embed_icon('clean') ?>Pulisci log</button>
			</form>
		</div>
	</div>

	<div class="btn js-copy-orders"><?= embed_icon('copy') ?>Copia tutto</div>
</div>

<p class="log">

	/// <?= $validCarts->count() ? $filteredOrders->count() : 0 ?> ARTICOLI DA FARE<br>

</p>

<div class="orders">

	<?php if ($validCarts->count()) { ?>

		<p class="log">
			--------------------------- <span class="text-strong">DESIGN</span> ---------------------------
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
					echo '<span class="text-color-highlighted">' . ($product->designId ?  $product->designId . ' - ' : '') . $product->productName . '</span><br>';

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
						$orderDescription = $order->inventory ? $orderDescription . ' <strong>(INVENTARIO)</strong>' : $orderDescription;

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
			--------------------------- <span class="text-strong">PRODOTTI</span> ---------------------------
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
					->where('inventory', 0)
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

</div>

{% end main %}
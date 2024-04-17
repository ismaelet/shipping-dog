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
				->sortBy('seller_id')
				->unique('seller_id');

			foreach ($sortedBySellers as $sellerOrder) {

				// Seller name
				echo '<span style="color: #ff9600;">[ ' . strtoupper($sellerOrder->storeName) . ' ]</span><br>';

				// Get the orders
				$sortedByProducts = $filteredOrders
					->where('seller_id', '=', $sellerOrder->seller_id)
					->sortBy('design_id')
					->unique('design_id');

				// Designs
				foreach ($sortedByProducts as $product) {
					$mergedOrders = [];

					// Product name (grouped by design_id)
					echo '<span class="text-color-highlighted">' . ($product->design_id ?  $product->design_id . ' - ' : '') . $product->productName . '</span><br>';

					// Get the orders
					$orders = $filteredOrders
						->where('seller_id', '=', $product->seller_id)
						->where('design_id', '=', $product->design_id)
						->sortBy('size_id')
						->sortBy('color_id')
						->sortBy('type_id');

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
				->sortBy('type_id')
				->unique('type_id');

			foreach ($sortedByTypes as $typeOrder) {

				$mergedOrders = [];

				// Seller name
				echo '<span style="color: #ff9600;">[ ' . strtoupper($typeOrder->productTypeName) . ' ]</span><br>';

				// Get the orders
				$sortedByData = $filteredOrders
					->where('inventory', 0)
					->where('type_id', '=', $typeOrder->type_id)
					->sortBy('color_id')
					->sortBy('size_id');

				// Merge orders
				foreach ($sortedByData as $order) {
					$orderDescription = [];
					$orderDescription[] = $order->size && strpos($order->product->type->sizes, ',') ? $order->size->name : null;
					$orderDescription[] = $order->color && strpos($order->product->type->colors, ',') ? $order->color->name : null;
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
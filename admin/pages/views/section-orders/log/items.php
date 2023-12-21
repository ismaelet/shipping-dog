<?php

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Order;

?>

{% block main %}

<p id="log">

	/// <?= $carts->count() ?> ARTICOLI DA FARE<br>

	<?php if ($firstCart) { ?>

		/// A PARTIRE DA ORDINE <span class="fw-bold">#<?= $firstCart->ID ?></span> (<span class="fw-bold"><?= $firstCart->address->aname ?></span>)

		<br><br><br>

	<?php
		foreach ($sellers as $seller) {
			// Get the orders
			$designs = Design::join('orders', 'designs.ID', '=', 'orders.designId')
				->join('carts', 'orders.cartID', '=', 'carts.ID')
				->where('carts.status', '>', 0)
				->where('carts.time', '>', $date)
				->where('sellerId', '=', $seller->userID)
				->orderBy('designs.name', 'asc')
				->get(['designs.*']);

			// Seller name
			echo '<span style="color: #ff9600;">[ ' . strtoupper($seller->name) . ' ]</span><br>';

			// Designs
			foreach ($designs as $design) {
				$orders = Order::join('carts', 'orders.cartID', '=', 'carts.ID')
					->where('carts.status', '>', 0)
					->where('carts.time', '>', $date)
					->where('designId', '=', $design->ID)
					->where('sellerId', '=', $seller->userID)
					->orderBy('typeID', 'asc')
					->orderBy('sizeID', 'asc')
					->orderBy('colorID', 'asc')
					->get(['orders.*']);

				// Design name
				echo '<span style="color: #fff;">' . $design->ID . ' - ' . $design->name . '</span><br>';

				// Orders
				foreach ($orders as $order) {
					echo $order->quantity . ' x ' . $order->description() . '<br>';
				}

				echo '<br>';
			}

			echo '<br>';
		}
	} ?>

</p>

<form method="POST" class="flex-group center-content mar-t-2">
	<button type="submit" name="submit" class="button" onclick="return confirm('Sei sicuro di voler pulire il log?')">Pulisci log</button>
</form>

{% end main %}
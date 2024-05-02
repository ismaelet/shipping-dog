<?php

namespace Legacy\Classes\ViewManager\Ecommerce;

use App\Models\Ecommerce\Order;
use Illuminate\Support\Facades\Auth;
use Legacy\Classes\Ecommerce\Customer;

class OrderList {

	private $customer;

	function __construct(object $customer = null) {
		$this->customer = $customer ?: new Customer(Auth::id());
	}

	/*
	* Render methods
	*/
	public function render() {
		ob_start();

		if ($this->customer->confirmedordersCount) { ?>

			<section class="cart-list">
				<div class="cart-list__wrapper">
					<div class="cart-list__header">
						<p class="cart-list__field cart-list__field--header">Ordine</p>
						<p class="cart-list__field cart-list__field--header">Prodotti</p>
						<p class="cart-list__field cart-list__field--header">Totale</p>
						<p class="cart-list__field cart-list__field--header">Stato</p>
						<p class="cart-list__field cart-list__field--header">Data</p>
						<div class="cart-list__field cart-list__field--header"></div>
					</div>

					<?php foreach ($this->customer->confirmedorders as $order) {
						$order = Order::find($order->id);
					?>

						<div class="cart-list__cart">
							<p class=" cart-list__field"><?= $order->idCode() ?></p>
							<p class="cart-list__field">

								<?php foreach ($order->order_items as $orderItem) {
									echo $orderItem->name() . '<br>';
								} ?>

							</p>
							<p class="cart-list__field"><?= $order->priceString() ?></p>
							<p class="cart-list__field">
								<?= $order->statusLabel() ?>
							</p>
							<p class="cart-list__field"><?= $order->createdAtString() ?></p>
							<p class="cart-list__field">
								<a class="cart-list__link text-link" href="<?= $order->recapUrl() ?>">
									visualizza
								</a>
							</p>
						</div>

					<?php } ?>

				</div>
			</section>

		<?php } else { ?>

			<p class="">Nessun ordine effetuato</p>

<?php }
		return ob_get_clean();
	}
}

<?php

namespace ViewManager\Ecommerce;

use App\Models\Ecommerce\Cart;

use function Ecommerce\customer;

class CartList {

	private $customer;

	function __construct(object $customer = null) {
		$this->customer = $customer ?: customer();
	}

	/*
	* Render methods
	*/
	public function render() {
		ob_start();

		if ($this->customer->confirmedCartsCount) { ?>

			<section class="cart-list">
				<div class="cart-list__wrapper">
					<div class="cart-list__header">
						<p class="cart-list__field cart-list__field--header">Ordine</p>
						<p class="cart-list__field cart-list__field--header">Prodotto</p>
						<p class="cart-list__field cart-list__field--header">Totale</p>
						<p class="cart-list__field cart-list__field--header">Stato</p>
						<p class="cart-list__field cart-list__field--header">Data</p>
						<div class="cart-list__field cart-list__field--header"></div>
					</div>

					<?php foreach ($this->customer->confirmedCarts as $cart) {
						$cart = Cart::find($cart->id);
					?>

						<div class="cart-list__cart">
							<p class=" cart-list__field"><?= $cart->idCode() ?></p>
							<p class="cart-list__field">

								<?php foreach ($cart->orders as $order) {
									echo $order->name() . '<br>';
								} ?>

							</p>
							<p class="cart-list__field"><?= $cart->priceString() ?></p>
							<p class="cart-list__field">
								<span class="cart-list__status background-status-color--<?= $cart->statusCode() ?>">
									<?= $cart->statusName() ?>
								</span>
							</p>
							<p class="cart-list__field"><?= $cart->timeString() ?></p>
							<p class="cart-list__field">
								<a class="cart-list__link text-link" href="<?= $cart->url() ?>">
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

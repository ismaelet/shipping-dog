<?php

namespace Legacy\Classes\ViewManager\Ecommerce;



class CartRecap {

	public static function render(object $cart = null) {
		$cart = $cart ?: cart_old();

		ob_start();
?>

		<div id="rec-pan">
			<h1 class="title">RIEPILOGO</h1>
			<div id="chck_box">
				<div class="chck_item">
					<p class="chck_name">Articoli</p>
					<p class="chck_pric js-cart-products-price"><?= $cart->fullProductPriceString ?></p>
				</div>

				<div class="chck_item">
					<p class="chck_name">Spedizione</p>
					<p id="chck_totShip" class="chck_pric js-cart-shipping-price-string js-recap-shipping-price" data-shipping-method-id="<?= $cart->shippingMethodId ?>"><?= $cart->shippingFeeString ?></p>
				</div>

				<?php if ($cart->discount) { ?>

					<div class="chck_item">
						<p class="chck_name">Sconto</p>
						<p id="chck_totShip" class="chck_pric js-cart-discount">- <?= $cart->discountString ?></p>
					</div>

				<?php } ?>

				<?php if ($cart->usedCredit) { ?>

					<div class="chck_item">
						<p class="chck_name" style="color: #ff0000;">Credito</p>
						<p id="chck_totShip" class="chck_pric" style="color: #ff0000;">-<?= $cart->usedCreditString ?></p>
					</div>

				<?php } ?>

				<div id="total" class="chck_item">
					<p class="chck_name">Totale</p>
					<p class="chck_pric js-cart-price-string"><?= $cart->priceString ?></p>
				</div>

				<?php if (!$cart->status) { ?>

					<div id="chck_btn_box" class="grid gap1">
						<button type="button" id="checkout" class="btn button--full-width" onclick="$('#submit').click();">Prosegui</button>
						<a class="btn btn--secondary button--full-width" href="<?= client_url('/shop') ?>">Continua con gli acquisti</a>
					</div>


				<?php } ?>

			</div>
		</div>

<?php
		return ob_get_clean();
	}
}

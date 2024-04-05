<?php

use Ecommerce\Cart;

function cart_setup_old($buyerID, $stepId = false) {

	// setup cart
	$cartID = DBOld::find('ID FROM carts WHERE buyer=? and status=0', [$buyerID]);

	// redirect if the cart doesn't exists
	if (!$cartID) redirect_old('/cart/empty');

	// clean and redirect if the cart is empty
	if (!cart_quantity()) {

		DBOld::delete('carts WHERE ID = ?', $cartID);

		redirect_old('/cart/empty');
	}

	// create cart
	$cart = new Cart($cartID);

	// redirect if the step is wrong
	if ($stepId && $cart->step < $stepId) redirect_old('/cart?step=' . $cart->step);

	// clean the code if doesn't exit anymore or is expired
	$code = $cart->code;

	if ($code) {

		$valid = false;

		$row = DBOld::row('time_start, time_end FROM codes WHERE code=?', $code);

		if ($row && strtotime($row['time_end']) > strtotime('now') && strtotime($row['time_start']) < strtotime('now')) $valid = true;

		if (!$valid) {
			$cart->removeCoupon();
		}
	}

	// check for promotions
	cart_promotions($cart);

	return $cart;
}


function cart_promotions(&$cart) {

	/* edit from here */
	$promotion['active'] = false;
	$promotion['name'] = 'Promozione felpe';
	$promotion['code'] = 'hbc-felpe';
	$promotion['info'] = 'Hai ottenuto il <strong>20% di sconto</strong> su tutti gli articoli per avere nel carrello almeno due felpe del negozio <strong>Human Body Collection</strong>.';
	$promotion['discount-sd'] = 10;
	$promotion['discount-shop'] = 10;
	$promotion['trigger'] = [
		'count',
		'orders WHERE buyer=? AND itemID IN (SELECT ID FROM items WHERE userID = ?) AND typeID IN (6,7,8,9,10,11)',
		[$cart->customer, 187],
		2
	];
	/* until here */

	//check for promotions if the cart is active
	if (!$cart->status) {

		//remove expired promotions
		if ($cart->promotion && ($cart->promotion != $promotion['code'] || !$promotion['active'])) {

			DBOld::update('carts SET promotion="" WHERE ID = ?', [$cart->id]);
			DBOld::update('orders SET price_disc=0, gain_disc=0 WHERE cartID = ?', [$cart->id]);

			redirect_old();
		}

		//in case of active promotion
		if ($promotion['active']) {

			$cart->promotion_data = $promotion;

			//add the promotion
			if (!$cart->code) {

				$triggered = DBOld::count($promotion['trigger'][1], $promotion['trigger'][2]) >= $promotion['trigger'][3];

				//if triggered
				if ($triggered && !$cart->promotion) {

					$promotion['discount'] = ($promotion['discount-sd'] + $promotion['discount-shop']) / 100;

					//add promotion to the cart
					$cart->promotion = $promotion['code'];

					DBOld::update('carts SET promotion=? WHERE ID = ?', [$promotion['code'], $cart->id]);

					//add discount to orders
					$orders = DBOld::select('ID, price FROM orders WHERE cartID = ?', [$cart->id]);

					while ($order = $orders->fetch_assoc()) {

						$price_disc = round($order['price'] * $promotion['discount'], 2);
						$gain_disc = round($order['price'] * $promotion['discount-shop'] / 100, 2);

						DBOld::update('orders SET price_disc=?, gain_disc=? WHERE ID = ?', [$price_disc, $gain_disc, $order['ID']]);
					}

					redirect_old();
				}

				//if not triggered anymore
				if ($cart->promotion && !$triggered) {

					DBOld::update('carts SET promotion="" WHERE ID = ?', [$cart->id]);
					DBOld::update('orders SET price_disc=0, gain_disc=0 WHERE cartID = ?', [$cart->id]);

					redirect_old();
				}
			}
		}
	}
}

function cart_flow($cart, $loc) {
	$stepIds = ['Accedi', 'Spedizione', 'Offerte', 'Conferma'];

	use_natural_keys($stepIds);
?>

	<section id="flow-bar">

		<?php foreach ($stepIds as $key => $stepId) { ?>

			<!-- Line -->
			<?php if ($key > 1) { ?>
				<div class="flow-line <?= $key <= $cart->step ? 'active' : '' ?>"></div>
			<?php } ?>

			<!-- Step -->
			<div class="flow-step <?= $key <= $cart->step ? 'active' : '' ?> <?= $key == $loc ? 'this' : '' ?>">

				<!-- Number -->
				<a <?= $key <= $cart->step && $key > 1 && $loc != $key ? 'href="' . client_url('/cart?step=' . $key) . '"' : '' ?> class="flow-circle center-items">

					<?php if ($key < $cart->step) { ?>

						<div class="flow-icon icon-box">
							<?= icon_embed('correct') ?>
						</div>

					<?php } else { ?>

						<p class="flow-num"><?= $key ?></p>

					<?php } ?>

				</a>

				<!-- Text -->
				<p class="flow-txt"><?= $stepId ?></p>

			</div>

		<?php } ?>

	</section>

<?php }

function cart_recap($cart, $active = true) {
	$credit_used = 0;
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
				<p id="chck_totShip" class="chck_pric js-cart-shipping-price-string js-recap-shipping-price" data-shipping-method-id="<?= $cart->shippingMethodId ?>"><?= $cart->shippingPriceString ?></p>
			</div>

			<?php if ($cart->discount) { ?>
				<div class="chck_item">
					<p class="chck_name">Sconto</p>
					<p id="chck_totShip" class="chck_pric js-cart-discount">- <?= $cart->discountString ?></p>
				</div>
			<?php } ?>

			<?php if ($credit_used) { ?>
				<div class="chck_item">
					<p class="chck_name" style="color: #ff0000;">Credito</p>
					<p id="chck_totShip" class="chck_pric" style="color: #ff0000;"><?= format(-$credit_used) ?>€</p>
				</div>
			<?php } ?>

			<div id="total" class="chck_item">
				<p class="chck_name">Totale</p>
				<p class="chck_pric js-cart-price-string"><?= $cart->priceString ?></p>
			</div>

			<?php if ($active) { ?>

				<div id="chck_btn_box" class="grid gap1">
					<button type="button" id="checkout" class="button button--full-width" onclick="$('#submit').click();">Prosegui</button>
					<a class="button button--secondary button--full-width" href="<?= client_url('/shop') ?>">Continua con gli acquisti</a>
				</div>

			<?php } ?>

		</div>
	</div>

<?php }

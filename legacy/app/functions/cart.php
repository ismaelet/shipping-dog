<?php

use App\Models\Ecommerce\Order;
use Legacy\Classes\Ecommerce\OrderItem;

function cart_setup_old($user_idID, $stepId = false) {

	// setup cart
	$order_id = \Legacy\System\Classes\LegacyDB::find('id FROM orders WHERE user_id=? and status=0', [$user_idID]);

	// redirect if the cart doesn't exists
	if (!$order_id) redirect_old('/cart/empty');

	// clean and redirect if the cart is empty
	if (!cart_quantity()) {

		\Legacy\System\Classes\LegacyDB::delete('orders WHERE id = ?', $order_id);

		redirect_old('/cart/empty');
	}

	// create cart
	$order = new Cart($order_id);

	// redirect if the step is wrong
	if ($stepId && $order->step < $stepId) redirect_old('/cart?step=' . $order->step);

	// clean the code if doesn't exit anymore or is expired
	$code = $order->code;

	if ($code) {

		$valid = false;

		$row = \Legacy\System\Classes\LegacyDB::row('time_start, time_end FROM codes WHERE code=?', $code);

		if ($row && strtotime($row['time_end']) > strtotime('now') && strtotime($row['time_start']) < strtotime('now')) $valid = true;

		if (!$valid) {
			$order->removeCoupon();
		}
	}

	// check for promotions
	cart_promotions($order);

	return $order;
}


function cart_promotions(&$order) {

	/* edit from here */
	$promotion['active'] = false;
	$promotion['name'] = 'Promozione felpe';
	$promotion['code'] = 'hbc-felpe';
	$promotion['info'] = 'Hai ottenuto il <strong>20% di sconto</strong> su tutti gli articoli per avere nel carrello almeno due felpe del negozio <strong>Human Body Collection</strong>.';
	$promotion['discount-sd'] = 10;
	$promotion['discount-shop'] = 10;
	$promotion['trigger'] = [
		'count',
		'order_items WHERE user_id=? AND product_id IN (SELECT id FROM products WHERE user_id = ?) AND type_id IN (6,7,8,9,10,11)',
		[$order->customer, 187],
		2
	];
	/* until here */

	//check for promotions if the cart is active
	if (!$order->status) {

		//remove expired promotions
		if ($order->promotion && ($order->promotion != $promotion['code'] || !$promotion['active'])) {

			\Legacy\System\Classes\LegacyDB::update('orders SET promotion="" WHERE id = ?', [$order->id]);
			\Legacy\System\Classes\LegacyDB::update('order_items SET price_disc=0, gain_disc=0 WHERE order_id = ?', [$order->id]);

			redirect_old();
		}

		//in case of active promotion
		if ($promotion['active']) {

			$order->promotion_data = $promotion;

			//add the promotion
			if (!$order->code) {

				$triggered = \Legacy\System\Classes\LegacyDB::count($promotion['trigger'][1], $promotion['trigger'][2]) >= $promotion['trigger'][3];

				//if triggered
				if ($triggered && !$order->promotion) {

					$promotion['discount'] = ($promotion['discount-sd'] + $promotion['discount-shop']) / 100;

					//add promotion to the cart
					$order->promotion = $promotion['code'];

					\Legacy\System\Classes\LegacyDB::update('orders SET promotion=? WHERE id = ?', [$promotion['code'], $order->id]);

					//add discount to order_items
					$order_items = \Legacy\System\Classes\LegacyDB::select('id, price FROM order_items WHERE order_id = ?', [$order->id]);

					while ($orderItem = $order_items->fetch_assoc()) {

						$price_disc = round($orderItem['price'] * $promotion['discount'], 2);
						$gain_disc = round($orderItem['price'] * $promotion['discount-shop'] / 100, 2);

						\Legacy\System\Classes\LegacyDB::update('order_items SET price_disc=?, gain_disc=? WHERE id = ?', [$price_disc, $gain_disc, $orderItem['id']]);
					}

					redirect_old();
				}

				//if not triggered anymore
				if ($order->promotion && !$triggered) {

					\Legacy\System\Classes\LegacyDB::update('orders SET promotion="" WHERE id = ?', [$order->id]);
					\Legacy\System\Classes\LegacyDB::update('order_items SET price_disc=0, gain_disc=0 WHERE order_id = ?', [$order->id]);

					redirect_old();
				}
			}
		}
	}
}

function cart_flow($order, $loc) {
	$stepIds = ['Accedi', 'Spedizione', 'Offerte', 'Conferma'];

	use_natural_keys($stepIds);
?>

	<section id="flow-bar">

		<?php foreach ($stepIds as $key => $stepId) { ?>

			<!-- Line -->
			<?php if ($key > 1) { ?>
				<div class="flow-line <?= $key <= $order->step ? 'active' : '' ?>"></div>
			<?php } ?>

			<!-- Step -->
			<div class="flow-step <?= $key <= $order->step ? 'active' : '' ?> <?= $key == $loc ? 'this' : '' ?>">

				<!-- Number -->
				<a <?= $key <= $order->step && $key > 1 && $loc != $key ? 'href="' . client_url('/cart?step=' . $key) . '"' : '' ?> class="flow-circle center-items">

					<?php if ($key < $order->step) { ?>

						<div class="flow-icon icon-box">
							<?= embed_icon('correct') ?>
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

function cart_recap($order, $active = true) {
	$order = EcommerceOrder::find($order->id);
?>

	<div id="rec-pan">

		<h1 class="title">RIEPILOGO</h1>

		<div id="chck_box">

			<div class="chck_item">
				<p class="chck_name">Articoli</p>
				<p class="chck_pric js-cart-products-price"><?= $order->grossOrderItemsPriceString() ?></p>
			</div>

			<div class="chck_item">
				<p class="chck_name">Spedizione</p>
				<p id="chck_totShip" class="chck_pric js-cart-shipping-price-string js-recap-shipping-price" data-shipping-method-id="<?= $order->shipping_method_id ?>"><?= $order->shippingFeeString() ?></p>
			</div>

			<?php if ($order->orderItemsDiscount()) { ?>
				<div class="chck_item">
					<p class="chck_name">Sconto</p>
					<p id="chck_totShip" class="chck_pric js-cart-discount">- <?= $order->orderItemsDiscountString() ?></p>
				</div>
			<?php } ?>

			<div id="total" class="chck_item">
				<p class="chck_name">Totale</p>
				<p class="chck_pric js-cart-price-string"><?= $order->priceString() ?></p>
			</div>

			<?php if ($active) { ?>

				<div id="chck_btn_box" class="grid gap1">
					<button type="button" id="checkout" class="btn button--full-width" onclick="$('#submit').click();">Prosegui</button>
					<a class="btn btn--secondary button--full-width" href="<?= client_url('/shop') ?>">Continua con gli acquisti</a>
				</div>

			<?php } ?>

		</div>
	</div>

<?php }

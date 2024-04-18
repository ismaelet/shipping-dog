<?php

use App\Helpers\StringHelper;

use function Ecommerce\ecommerce_config;

$cart = cart();

?>

{% block main %}

<form id="pp_form" class="pp_form" action="https://www.paypal.com/cgi-bin/webscr" method="POST">

	<!--PAYPAL DATA-->
	<input type="hidden" name="cmd" value="_cart">
	<input type="hidden" name="business" value="<?= ecommerce_config('paypal-user') ?>">
	<input type="hidden" name="upload" value="1">
	<input type="hidden" name="lc" value="it_IT">
	<input type="hidden" name="currency_code" value="EUR">

	<!-- Orders -->
	<?php foreach ($cart->orders as $orderKey => $order) { ?>

		<input type="hidden" name="item_name_<?= ($orderKey + 1) ?>" value="<?= str_replace('"', '\'', $order->description()) ?>">
		<input type="hidden" name="amount_<?= ($orderKey + 1) ?>" value="<?= StringHelper::formatWithDot($order->grossPrice()) ?>">
		<input type="hidden" name="quantity_<?= ($orderKey + 1) ?>" value="<?= $order->quantity ?>">
		<input type="hidden" name="item_number_<?= ($orderKey + 1) ?>" value="<?= $order->id ?>">

	<?php } ?>

	<!-- Discount -->
	<?php if ($cart->discount()) { ?>
		<input type="hidden" name="discount_amount_cart" value="<?= StringHelper::formatWithDot($cart->discount()) ?>">
	<?php } ?>

	<!-- Shipping data -->
	<?php $row_addr = DBOld::row('* FROM addresses WHERE cart_id = ?', [$cart->id]) ?>

	<input type="hidden" name="shipping_1" value="<?= StringHelper::formatWithDot($cart->shippingPrice()) ?>">
	<input type="hidden" name="first_name" value="<?= explode(' ', $cart->address->full_name, 2)[0] ?>">
	<input type="hidden" name="last_name" value="<?= explode(' ', $cart->address->full_name, 2)[1] ?? '' ?>">
	<input type="hidden" name="address1" value="<?= $cart->address->address . ', ' . $cart->address->number ?>">
	<input type="hidden" name="city" value="<?= $cart->address->city ?>">
	<input type="hidden" name="state" value="<?= $cart->address->region ?>">
	<input type="hidden" name="zip" value="<?= $cart->address->zip ?>">
	<input type="hidden" name="lc" value="IT">
	<input type="hidden" name="country" value="IT">
	<input type="hidden" name="email" value="<?= $cart->address->email ?>">
	<input type="hidden" name="night_phone_a" value="<?= $cart->address->phone ?>">

	<!--CUSTOM DATA-->
	<input type="hidden" name="custom" value="<?= $cart->id . '&' . $cart->user_id . '&it' ?>">
</form>

<!-- Load icon -->
<div class="load-icon"></div>

<script>
	document.getElementById('pp_form').submit();
</script>

{% end main %}
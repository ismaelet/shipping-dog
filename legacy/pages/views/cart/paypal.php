<?php

use App\Helpers\StringHelper;

$order = cart();

?>

{% block main %}

<form id="pp_form" class="pp_form" action="https://www.paypal.com/cgi-bin/webscr" method="POST">

	<!--PAYPAL DATA-->
	<input type="hidden" name="cmd" value="_cart">
	<input type="hidden" name="business" value="<?= ecommerce_config('paypal-user') ?>">
	<input type="hidden" name="upload" value="1">
	<input type="hidden" name="lc" value="it_IT">
	<input type="hidden" name="currency_code" value="EUR">

	<!-- order_items -->
	<?php foreach ($order->order_items as $orderItemKey => $orderItem) { ?>

		<input type="hidden" name="item_name_<?= ($orderItemKey + 1) ?>" value="<?= str_replace('"', '\'', $orderItem->description()) ?>">
		<input type="hidden" name="amount_<?= ($orderItemKey + 1) ?>" value="<?= StringHelper::formatWithDot($orderItem->grossPrice()) ?>">
		<input type="hidden" name="quantity_<?= ($orderItemKey + 1) ?>" value="<?= $orderItem->quantity ?>">
		<input type="hidden" name="item_number_<?= ($orderItemKey + 1) ?>" value="<?= $orderItem->id ?>">

	<?php } ?>

	<!-- Discount -->
	<?php if ($order->discount()) { ?>
		<input type="hidden" name="discount_amount_cart" value="<?= StringHelper::formatWithDot($order->discount()) ?>">
	<?php } ?>

	<!-- Shipping data -->
	<?php $row_addr = \Legacy\System\Classes\LegacyDB::row('* FROM addresses WHERE order_id = ?', [$order->id]) ?>

	<input type="hidden" name="shipping_1" value="<?= StringHelper::formatWithDot($order->shippingFee()) ?>">
	<input type="hidden" name="first_name" value="<?= explode(' ', $order->address->full_name, 2)[0] ?>">
	<input type="hidden" name="last_name" value="<?= explode(' ', $order->address->full_name, 2)[1] ?? '' ?>">
	<input type="hidden" name="address1" value="<?= $order->address->address . ', ' . $order->address->number ?>">
	<input type="hidden" name="city" value="<?= $order->address->city ?>">
	<input type="hidden" name="state" value="<?= $order->address->region ?>">
	<input type="hidden" name="zip" value="<?= $order->address->zip ?>">
	<input type="hidden" name="lc" value="IT">
	<input type="hidden" name="country" value="IT">
	<input type="hidden" name="email" value="<?= $order->address->email ?>">
	<input type="hidden" name="night_phone_a" value="<?= $order->address->phone ?>">

	<!--CUSTOM DATA-->
	<input type="hidden" name="custom" value="<?= $order->id . '&' . $order->user_id . '&it' ?>">
</form>

<!-- Load icon -->
<div class="load-icon"></div>

<script>
	document.getElementById('pp_form').submit();
</script>

{% end main %}
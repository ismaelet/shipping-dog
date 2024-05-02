<?php

$cart = cart();
$warning = warning_banner();

// setup cart
if (!$cart->price) {
	$res_payments = \Legacy\System\Classes\LegacyDB::select('* FROM sys_payment WHERE id = 4');

	$cart->payment_method_id = 4;
	$cart->save();
} else if ($cart->localDelivery) {
	$res_payments = \Legacy\System\Classes\LegacyDB::select('* FROM sys_payment WHERE id IN (1,3)');
} else {
	$res_payments = \Legacy\System\Classes\LegacyDB::select('* FROM sys_payment WHERE active = 1 ORDER BY id ASC');
}

// checkout
if (isset($_POST['submit'])) {
	$paymentMethodId = $_POST['cbx_pay'];

	$cart->payment_method_id = $paymentMethodId;
	$cart->save();

	if ($paymentMethodId == 1) {
		redirect_old('/paypal/order/create');
	}

	redirect_old('/pay/promotion');
}

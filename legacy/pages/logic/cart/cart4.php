<?php

use Illuminate\Support\Str;

$cart = cart();

// setup cart
if ($cart->price == 0) {
	$res_payments = \Legacy\System\Classes\LegacyDB::select('* FROM sys_payment WHERE id = 4');

	$cart->payment_method_id = 4;
	$cart->save();
} else if ($cart->localDelivery) {
	$res_payments = \Legacy\System\Classes\LegacyDB::select('* FROM sys_payment WHERE id IN (1,3)');

	if (!in_array($cart->payment_method_id, [1, 3])) {
		$cart->payment_method_id = 1;
		$cart->save();
	}
} else {
	$res_payments = \Legacy\System\Classes\LegacyDB::select('* FROM sys_payment WHERE active = 1 ORDER BY id ASC');

	if ($cart->payment_method_id != 1) {
		$cart->payment_method_id = 1;
		$cart->save();
	}
}

// checkout
if (isset($_POST['submit'])) {
	$paymentMethodId = $_POST['payment_method_id'];

	$cart->payment_method_id = $paymentMethodId;
	$cart->save();

	if ($paymentMethodId == 1) {
		redirect_old($cart->paymentUrl());
	} else if (in_array($paymentMethodId, [3, 4])) {
		$token = Str::random(20);

		$cart->token = $token;
		$cart->save();

		redirect_old('/pay/promotion?token=' . $token);
	}
}

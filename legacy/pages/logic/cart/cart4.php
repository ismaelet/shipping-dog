<?php

// setup variables
$warning = warning_banner();

// setup cart
if (!cart_old()->price) {
	$res_payments = \Legacy\System\Classes\LegacyDB::select('* FROM sys_payment WHERE id = 4');

	cart_old()->updatePaymentMethodId(4);
} else if (cart_old()->localDelivery) {
	$res_payments = \Legacy\System\Classes\LegacyDB::select('* FROM sys_payment WHERE id IN (1,3)');
} else {
	$res_payments = \Legacy\System\Classes\LegacyDB::select('* FROM sys_payment WHERE active = 1 ORDER BY id ASC');
}

// checkout
if (isset($_POST['submit'])) {
	$paymentMethodId = $_POST['cbx_pay'];

	cart_old()->updatePaymentMethodId($paymentMethodId);

	switch ($paymentMethodId) {
		case 1:
			redirect_old('/pay/paypal');
		case 2:
			redirect_old('/pay/stripe');
		default:
			redirect_old('/pay/promotion');
	}
}

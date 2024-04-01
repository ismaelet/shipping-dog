<?php

use function Ecommerce\cart;

// setup variables
$warning = warning_banner();

// setup cart
if (!cart()->price) {
	$res_payments = DB::select('* FROM sys_payment WHERE ID = 4');

	cart()->updatePaymentMethodId(4);
} else if (cart()->localDelivery) {
	$res_payments = DB::select('* FROM sys_payment WHERE ID IN (1,3)');
} else {
	$res_payments = DB::select('* FROM sys_payment WHERE active = 1 ORDER BY ID ASC');
}

// checkout
if (isset($_POST['submit'])) {
	$paymentMethodId = $_POST['cbx_pay'];

	cart()->updatePaymentMethodId($paymentMethodId);

	switch ($paymentMethodId) {
		case 1:
			redirect('/pay/paypal');
		case 2:
			redirect('/pay/stripe');
		default:
			redirect('/pay/promotion');
	}
}

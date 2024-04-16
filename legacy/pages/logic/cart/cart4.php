<?php



// setup variables
$warning = warning_banner();

// setup cart
if (!\App\Helpers\App::cartOld()->price) {
	$res_payments = DBOld::select('* FROM sys_payment WHERE id = 4');

	\App\Helpers\App::cartOld()->updatePaymentMethodId(4);
} else if (\App\Helpers\App::cartOld()->localDelivery) {
	$res_payments = DBOld::select('* FROM sys_payment WHERE id IN (1,3)');
} else {
	$res_payments = DBOld::select('* FROM sys_payment WHERE active = 1 ORDER BY id ASC');
}

// checkout
if (isset($_POST['submit'])) {
	$paymentMethodId = $_POST['cbx_pay'];

	\App\Helpers\App::cartOld()->updatePaymentMethodId($paymentMethodId);

	switch ($paymentMethodId) {
		case 1:
			redirect_old('/pay/paypal');
		case 2:
			redirect_old('/pay/stripe');
		default:
			redirect_old('/pay/promotion');
	}
}

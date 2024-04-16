<?php

include(substr(__DIR__, 0, strpos(__DIR__, 'listeners')) . '/system/setup/setup.php');

$ord_str = '';

$cart_id = $_POST['cart_id'];
$user->user_idId = $_POST['user_id'];

$res_cart = mysqli_query($con, "SELECT id FROM carts WHERE id=$cart_id AND user_id='$user->user_idId' AND status=0");
if (mysqli_num_rows($res_cart)) {

	include_once('shared.php');
	$_SESSION['payment_cart_id'] = $cart_id;
	$_SESSION['payment_type'] = 'buy';
	echo 'success';
} else {
	$_SESSION['err_stripe'] = 'err';
	echo 'error';
}

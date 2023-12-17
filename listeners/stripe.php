<?php

include(substr(__DIR__, 0, strpos(__DIR__, 'listeners')) . '/system/setup/setup.php');

$ord_str = '';

$cartID = $_POST['cartID'];
$user->buyerId = $_POST['buyer'];

$res_cart = mysqli_query($con, "SELECT ID FROM carts WHERE ID=$cartID AND buyer='$user->buyerId' AND status=0");
if (mysqli_num_rows($res_cart)) {

	include_once('shared.php');
	$_SESSION['payment_cartID'] = $cartID;
	$_SESSION['payment_type'] = 'buy';
	echo 'success';
} else {
	$_SESSION['err_stripe'] = 'err';
	echo 'error';
}

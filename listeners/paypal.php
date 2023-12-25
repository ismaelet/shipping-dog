<?php

include(substr(__DIR__, 0, strpos(__DIR__, 'listeners')) . '/system/setup/setup.php');

//setup variables
$ord_str = '';
$i = 1;
$codUsed = false;

//get raw data from POST
$raw_post_data = file_get_contents('php://input');

//convert raw data tu array
$raw_post_array = explode('&', $raw_post_data);

//separate variabile and value in array
$myPost = array();

foreach ($raw_post_array as $keyval) {

	$keyval = explode('=', $keyval);

	if (count($keyval) == 2) $myPost[$keyval[0]] = urldecode($keyval[1]);
}

$req = 'cmd=_notify-validate';

foreach ($myPost as $key => $value) {

	$value = urlencode($value);

	$req .= "&$key=$value";
}

//post IPN data back to paypal to validate
$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

if (!($res = curl_exec($ch))) {

	curl_close($ch);

	exit;
}

curl_close($ch);

//process the transaction
if ($res == 'VERIFIED') {

	//get status and email
	$status = $_POST['payment_status'];

	if ($status == 'Completed') {

		//GET PAYMENT DATA DATA
		$custom = isset($_POST['custom']) ? $_POST['custom'] : '';
		$cust_array = explode('&', $custom);

		$cartID = $cust_array[0];

		if (isset($cust_array[1])) {

			//external payment
			if ($cust_array[1] == 'custom') {

				file_get_contents('https://judgesgarden.com/listeners/paypal.php?id=' . $cartID);

				//refund or change
			} else if ($cust_array[1] == 'ship') mysqli_query($con, "UPDATE refunds SET status=4, payed=1 WHERE cartID=$cartID");

			//normal payment 
			else {

				$lang = $cust_array[2];

				include_once('shared.php');
			}
		} else error_log('Errore PayPal');
	}
}

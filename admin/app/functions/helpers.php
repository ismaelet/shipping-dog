<?php

function get_paypal_data(): stdClass {
	$clientId = 'AQlZc8C6_83ZpWe7sp64YauKVl94OSqdmWB9ptDVhdM6nyDmDy-JHq3tDDL3D8T9rR9muhgMo7h8rGrX';
	$clientSecret = 'REMOVED_SECRET';

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/oauth2/token');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
	curl_setopt($ch, CURLOPT_USERPWD, $clientId . ':' . $clientSecret);

	$headers = array();
	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/x-www-form-urlencoded';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);

	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}

	curl_close($ch);

	return json_decode($result);
}

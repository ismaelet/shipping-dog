<?php

/**
 * Get the PayPal access token
 */
function get_paypal_token() {
	$clientId = 'AQlZc8C6_83ZpWe7sp64YauKVl94OSqdmWB9ptDVhdM6nyDmDy-JHq3tDDL3D8T9rR9muhgMo7h8rGrX';
	$clientSecret = 'REMOVED_SECRET';

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://api.paypal.com/v1/oauth2/token');
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

	return json_decode($result)->access_token;
}

/**
 * Get the PayPal transactions
 */
function get_paypal_transactions() {

	$_SESSION['paypal_token'] = $accessToken = $_SESSION['paypal_token'] ?? get_paypal_token();

	$apiEndpoint = 'https://api.paypal.com/v1/reporting/transactions';

	// Calculate the start and end dates for the last month
	$startDate = date('Y-m-d\TH:i:s\Z', strtotime('2023-12-01'));
	$endDate = date('Y-m-d\TH:i:s\Z', strtotime('2023-12-18'));

	$queryParams = [
		'start_date' => $startDate,
		'end_date' => $endDate,
		'fields' => 'all',
		'page_size' => 500,
	];

	// Append query parameters to the endpoint
	$apiEndpoint .= '?' . http_build_query($queryParams);

	// Set up the cURL request
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $apiEndpoint);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPGET, 1);

	$headers = [
		'Accept: application/json',
		'Authorization: Bearer ' . $accessToken,
		'Content-Type: application/json',
	];

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	// Execute the cURL request
	$result = curl_exec($ch);

	// Check for cURL errors
	if (curl_errno($ch)) {
		echo 'Curl error: ' . curl_error($ch);
	}

	// Close cURL session
	curl_close($ch);

	// Decode and return the response
	return json_decode($result);
}

/**
 * Find the order by cart ID
 */
function paypal_find_order($id) {
	$_SESSION['paypal_order_items'] = $order_items = $_SESSION['paypal_order_items'] ?? get_paypal_transactions()->transaction_details;

	foreach ($order_items as $orderItem) {
		$order_id = explode('&', $orderItem->transaction_info->custom_field ?? null)[0];

		if ($order_id == $id) {
			return $orderItem;
		}
	}
}

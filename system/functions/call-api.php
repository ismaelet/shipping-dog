<?php

function callAPI($method, $url, $id = false, $data = false) {

	$url = $_SERVER['SERVER_NAME'] . CLIENT_ROOT . '/api/' . $url;
	if ($id) $url .= '/' . $id;

	$curl = curl_init();

	switch ($method) {
		case 'POST':
			curl_setopt($curl, CURLOPT_POST, 1);
			if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
			break;

		case 'PUT':
			curl_setopt($curl, CURLOPT_PUT, 1);
			break;
	}

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec($curl);
	$response = (array) json_decode($response, true);
	$response['response_status'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	if ($response['response_status'] != 200) echo $response['response_status'];

	return $response;
}

<?php

$queryConfig['select'] = ['user_id', 'name', 'request', 'stores.credit'];

//select fields
$fields = [
	['title' => 'PayPal', 'size' => 3, 'name' => 'user_id', 'type' => 'find', 'find' => 'paypal FROM payment_requests WHERE seller_id=?'],
	['title' => 'Richiesta', 'name' => 'request', 'type' => 'boolean-icon'],

	['type' => 'btn'],
];

$buttons = [
	['title' => 'Comunicazione', 'url' => PUBLIC_CLIENT_ROOT . '/uploads/documents/payments/Comunicazione_{{user_id}}.pdf', 'color' => 'gre'],
];

function _expand_results(&$entry, $field, $key, $row) {
	$seller = new Seller($row['user_id']);

	switch ($field['name']) {
		case 'earned':
			$entry['value'] = format_price($entry['value']);
			break;
		case 'payed':
			$entry['value'] = $seller->payedString;
			break;
		case 'otherTransfers':
			$entry['value'] = $seller->otherTransfersString;
			break;
		case 'credit':
			$entry['value'] = $seller->creditString;
			break;
		case 'toPay':
			$entry['value'] = $seller->toPayString;
			break;
	}
}

function _expand_buttons(&$button, $key, $row) {
	if ($key == 0 && !$row['request']) {
		$button = null;
	}
}

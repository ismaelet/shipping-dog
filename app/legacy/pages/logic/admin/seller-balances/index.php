<?php

/*
* Page configurations
*/

use App\Models\Ecommerce\Seller;

$pageConfig['add'] = false;

/*
* Query configuration
*/
$queryConfig['table'] = 'stores';
$queryConfig['index'] = 'id';
$queryConfig['from'] = 'stores';
$queryConfig['where-search'] = "name LIKE CONCAT('%',?,'%') OR user_id = ?";

//select fields
$fields = [
	['title' => 'id', 'name' => 'id'],
	['title' => 'Nome', 'name' => 'name', 'size' => 3],
	['title' => 'Totale', 'name' => 'earned', 'size' => 1],
	['title' => 'Pagato', 'name' => 'payed', 'size' => 1, 'not-sortable' => true],
	['title' => 'Altro', 'name' => 'otherTransfers', 'size' => 1, 'not-sortable' => true],
	['title' => 'Credito', 'name' => 'credit', 'size' => 1, 'not-sortable' => true],
	['title' => 'Da pagare', 'name' => 'toPay', 'size' => 1, 'not-sortable' => true],
	['title' => 'PayPal', 'size' => 3, 'name' => 'id', 'type' => 'find', 'find' => 'paypal FROM payment_requests WHERE seller_id=?'],
	['title' => 'Richiesta', 'name' => 'request', 'type' => 'boolean-icon'],

	['type' => 'btn'],
];

$buttons = [
	['title' => 'Comunicazione', 'url' => public_asset('documents/payments/Comunicazione_{{id}}.pdf'), 'color' => 'gre'],
];

function _expand_results(&$entry, $field, $key, $row) {
	$seller = Seller::find($row['id']);

	switch ($field['name']) {
		case 'earned':
			$entry['value'] = $seller->earnedString();
			break;
		case 'payed':
			$entry['value'] = $seller->payedString();
			break;
		case 'otherTransfers':
			$entry['value'] = $seller->otherTransferAmountString();
			break;
		case 'credit':
			$entry['value'] = $seller->creditString();
			break;
		case 'toPay':
			$entry['value'] = $seller->toBePayedString();
			break;
	}
}

function _expand_buttons(&$button, $key, $row) {
	if ($key == 0 && !$row['request']) {
		$button = null;
	}
}

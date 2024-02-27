<?php

/*
* Page configurations
*/

use Ecommerce\Seller;

use function Ecommerce\format_price;

$pageConfig['add'] = false;

/*
* Query configuration
*/
$queryConfig['table'] = 'stores';
$queryConfig['index'] = 'userID';
$queryConfig['select'] = ['userID', 'name', 'request', 'stores.credit'];
$queryConfig['from'] = '
	stores
	LEFT JOIN orders ON stores.userID = orders.sellerId
	INNER JOIN carts ON orders.cartID = carts.ID
';
$queryConfig['where'] = 'carts.status > 0';
$queryConfig['where-search'] = "name LIKE CONCAT('%',?,'%') OR userID = ?";
$queryConfig['group'] = 'orders.sellerId';

//select fields
$fields = [
	['title' => 'ID', 'name' => 'userID'],
	['title' => 'Nome', 'name' => 'name', 'size' => 3],
	['title' => 'Totale', 'name' => 'earned', 'size' => 1],
	['title' => 'Pagato', 'name' => 'payed', 'size' => 1, 'not-sortable' => true],
	['title' => 'Altro', 'name' => 'otherTransfers', 'size' => 1, 'not-sortable' => true],
	['title' => 'Credito', 'name' => 'credit', 'size' => 1, 'not-sortable' => true],
	['title' => 'Da pagare', 'name' => 'toPay', 'size' => 1, 'not-sortable' => true],
	['title' => 'PayPal', 'size' => 3, 'name' => 'userID', 'type' => 'find', 'find' => 'paypal FROM payment_requests WHERE shopId=?'],
	['title' => 'Richiesta', 'name' => 'request', 'type' => 'boolean-icon'],

	['type' => 'btn'],
];

$buttons = [
	['title' => 'Comunicazione', 'url' => PUBLIC_CLIENT_ROOT . '/uploads/documents/payments/Comunicazione_{{userID}}.pdf', 'color' => 'gre'],
];

function _expand_results(&$entry, $field, $key, $row) {
	$seller = new Seller($row['userID']);

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

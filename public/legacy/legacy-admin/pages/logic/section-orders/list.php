<?php

/*
* Page configuration
*/

use Ecommerce\Cart;

$pageConfig['add'] = false;
$pageConfig['row-action'] = 'edit?id={{id}}';

/*
* Query configuration
*/
$queryConfig['index'] = 'ID';
$queryConfig['table'] = 'carts';
$queryConfig['where'] = empty($_GET['search']) ? 'status <> 0' : '';
$queryConfig['maximum-results'] = 20;
$queryConfig['order-index'] = 'time';
$queryConfig['where-search'] = "
(
	ID = ? OR

	ID IN (
		SELECT cartID FROM carts_data WHERE comm_vip LIKE CONCAT('%',?,'%')
	) OR
	
	ID IN (
		SELECT cartID FROM addresses
		WHERE
			aname LIKE CONCAT('%',?,'%') OR
			fname LIKE CONCAT('%',?,'%') OR
			lname LIKE CONCAT('%',?,'%') OR
			email LIKE CONCAT('%',?,'%') OR
			city LIKE CONCAT('%',?,'%')
	) OR
	
	ID IN (
		SELECT cartID FROM orders
		WHERE
			itemID IN (SELECT ID FROM items WHERE name LIKE CONCAT('%',?,'%')) OR
			sellerId IN (SELECT userID FROM stores WHERE name LIKE CONCAT('%',?,'%')) OR
			designId IN (SELECT ID FROM designs WHERE ID = ? OR name LIKE CONCAT('%',?,'%'))
	) OR
	
	ID IN (SELECT cartID FROM orders WHERE typeID IN (SELECT ID FROM items_types WHERE name_it=CONCAT('%',?,'%')))
)";

/*
* Fields
*/
$fields = [
	['title' => 'ID'],
	['title' => 'Dati', 'size' => 1],
	['title' => 'Destinatario', 'size' => 1],
	['title' => 'Contatti',],
	['title' => 'Articoli', 'size' => 2],
	['title' => 'Totale'],
	['title' => 'Stato', 'wrap' => false],

	['type' => 'btn'],
];

$buttons = [
	['title' => 'Gestisci', 'url' => 'edit?id={{ID}}'],
	['title' => 'Rinvia l\'email', 'color' => 'green', 'name' => 'resend-status-email'],
];

//expand results
function _expand_results(&$entry, $field, $key, $row) {

	$cart = new Cart($row['ID']);

	if ($key == 0) {
		// id
		$entry['value'] = $cart->idCode;
	} else if ($key == 1) {
		// basic data
		$entry['value'] = $cart->dateString . '<br>';
		if (!$cart->customer->guest) $entry['value'] .= 'ID cliente: ' . $cart->customerId . '<br>';
		$entry['value'] .= 'Spedizione: ' . $cart->shippingMethod->shortName . '<br>';
		$entry['value'] .= 'Pagamento: ' . $cart->paymentMethod->name . '<br>';
		if ($cart->couponCode) $entry['value'] .= 'Codice sconto: ' . $cart->coupon->code . '<br>';
	} else if ($key == 2) {
		// address
		$entry['value'] = $cart->address->fullAddress;
	} else if ($key == 3) {
		// contacts
		$entry['value'] = $cart->address->contacts;
	} else if ($key == 4) {
		// orders
		if ($cart->quantity) {
			$entry['value'] = '';

			foreach ($cart->orders as $order) {
				$entry['value'] .= $order->name . '<br>';
			}
		} else $entry['value'] = '- Nessun articolo registrato -<br>';
	} else if ($key == 5) {
		// price
		$entry['value'] = $cart->priceString;
	} else if ($key == 6) {
		// status
		$entry['value'] = '<span class="order-status background-status-color--' . $cart->statusCode . '">' . $cart->adminStatusName . '</span>';
	}
}

// resend status email
if (isset($_POST['resend-status-email'])) {
	(new Cart($_POST['ID']))->sendStatusEmail(true);
}

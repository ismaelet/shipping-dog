<?php

/*
* Page configuration
*/

use App\Mail\OrderStatusMail;
use App\Models\Ecommerce\Order;
use Illuminate\Support\Facades\Mail;

$pageConfig['add'] = false;
$pageConfig['row-action'] = '{{id}}/edit';

/*
* Query configuration
*/
$queryConfig['index'] = 'id';
$queryConfig['table'] = 'orders';
$queryConfig['where'] = empty($_GET['search']) ? 'status <> 0' : '';
$queryConfig['maximum-results'] = 20;
$queryConfig['order-index'] = 'time';
$queryConfig['where-search'] = "
(
	ID = ? OR

	ID IN (
		SELECT order_id FROM addresses
		WHERE
			full_name LIKE CONCAT('%',?,'%') OR
			first_name LIKE CONCAT('%',?,'%') OR
			last_name LIKE CONCAT('%',?,'%') OR
			email LIKE CONCAT('%',?,'%') OR
			city LIKE CONCAT('%',?,'%')
	) OR
	
	ID IN (
		SELECT order_id FROM order_items
		WHERE
			product_id IN (SELECT id FROM products WHERE name LIKE CONCAT('%',?,'%')) OR
			seller_id IN (SELECT user_id FROM stores WHERE name LIKE CONCAT('%',?,'%')) OR
			design_id IN (SELECT id FROM designs WHERE id = ? OR name LIKE CONCAT('%',?,'%'))
	) OR
	
	ID IN (SELECT order_id FROM order_items WHERE type_id IN (SELECT id FROM product_types WHERE name=CONCAT('%',?,'%')))
)";

/*
* Fields
*/
$fields = [
	['title' => 'id'],
	['title' => 'Dati', 'size' => 1],
	['title' => 'Destinatario', 'size' => 1],
	['title' => 'Contatti',],
	['title' => 'Articoli', 'size' => 2],
	['title' => 'Totale'],
	['title' => 'Stato', 'wrap' => false],

	['type' => 'btn'],
];

$buttons = [
	['title' => 'Rinvia l\'email', 'color' => 'green', 'name' => 'resend-status-email'],
];

//expand results
function _expand_results(&$entry, $field, $key, $row) {

	$order = Order::find($row['id']);

	if ($key == 0) {
		// id
		$entry['value'] = $order->idCode();
	} else if ($key == 1) {
		// basic data
		$entry['value'] = $order->createdAtString() . '<br>';
		if ($order->customer) $entry['value'] .= 'ID cliente: ' . $order->customerId . '<br>';
		$entry['value'] .= $order->shipping_method->name . '<br>';
		$entry['value'] .= 'Pagamento: ' . $order->payment_method?->name . '<br>';
		if ($order->coupon) $entry['value'] .= 'Codice sconto: ' . $order->coupon->code . '<br>';
	} else if ($key == 2) {
		// address
		$entry['value'] = $order->address->fullAddress();
	} else if ($key == 3) {
		// contacts
		$entry['value'] = $order->address->contacts();
	} else if ($key == 4) {
		// order_items
		if ($order->quantity) {
			$entry['value'] = '';

			foreach ($order->order_items as $orderItem) {
				$entry['value'] .= $orderItem->fullName() . '<br>';
			}
		} else $entry['value'] = '- Nessun articolo registrato -<br>';
	} else if ($key == 5) {
		// price
		$entry['value'] = $order->priceString();
	} else if ($key == 6) {
		// status
		$entry['value'] = '<span class="order-status background-status-color--' . $order->statusTag() . '">' . $order->statusName() . '</span>';
	}
}

// resend status email
if (isset($_POST['resend-status-email'])) {
	$order = Order::find($_POST['id']);

	Mail::to($order->address->email)->send(new OrderStatusMail($order));
}

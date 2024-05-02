<?php

namespace Legacy\Classes\Ecommerce;

/*
* 2. Customer
*/

function customer() {
	global $customer;

	return $customer;
}

function customerId() {
	return customer()->id;
}

function isActiveCustomer() {
	return (bool) customerId();
}

function requireActiveCustomer() {
	global $customer;

	if (!isActiveCustomer()) {
		$customer = Customer::new();

		$_SESSION['customerId'] = $customer->id;
	}

	return $customer;
}

/*
* 3. Cart
*/
function associate_cart_setup_old($permanentCustomerId, $guestCustomerId = false) {
	global $customer;

	if (!empty($customer->id)) {
		$permanentCustomer = new Customer($permanentCustomerId);
		$guestCustomer = $guestCustomerId ? new Customer($guestCustomerId) : $customer;

		if (!$permanentCustomer->id) {
			\Legacy\System\Classes\LegacyDB::add('ecommerce_customers (userId)', [$permanentCustomerId]);
			$permanentCustomer = new Customer($permanentCustomerId);
		}

		if ($guestCustomer->hasCart) {
			if ($permanentCustomer->hasCart) {
				// if the user has a cart transfer the guest order_items to it
				\Legacy\System\Classes\LegacyDB::update('ecommerce_order_items SET order_id = ? WHERE order_id = ?', [$permanentCustomer->order->id, $guestCustomer->order->id]);
				\Legacy\System\Classes\LegacyDB::delete('ecommerce_orders WHERE id = ?', [$guestCustomer->order->id]);
			} else {
				// otherwise convert the session cart to the user cart
				\Legacy\System\Classes\LegacyDB::update('ecommerce_orders SET customerId=? WHERE id = ?', [$permanentCustomer->id, $guestCustomer->order->id]);
				\Legacy\System\Classes\LegacyDB::update('ecommerce_order_items SET customerId=? WHERE order_id = ?', [$permanentCustomer->id, $guestCustomer->order->id]);

				// add an address
				if (!$guestCustomer->order->addressId) {
					$addressId = \Legacy\System\Classes\LegacyDB::find('id FROM ecommerce_addresses WHERE customerId=? ORDER BY id DESC', [$permanentCustomer->id]);
					\Legacy\System\Classes\LegacyDB::update('ecommerce_orders SET addressId=? WHERE id = ?', [$addressId, $guestCustomer->order->id]);
				}
			}
		}

		$_SESSION['customerId'] = $permanentCustomerId;
	}
}

/*
* 4. Downloads
*/
function download_quote($order_id) {
	$debug = false;
	$order = new Cart($order_id);

	// products
	$productsHtml = [];

	foreach ($order->order_items as $orderItemKey => $orderItem) {
		$productsHtml[] = '
		<div class="product product--' . ($orderItemKey % 2 ? 'even' : 'odd') . '">
			<p>' . $orderItem->product->idCode . '</p>
			<p>' . $orderItem->product->name . '</p>
			<p>' . $orderItem->quantity . '</p>
			<p>' . $orderItem->netPriceString . '</p>
			<p>' . $orderItem->vatString . '</p>
			<p>' . $orderItem->priceString . '</p>
		</div>';
	}

	$productsHtml = implode('', $productsHtml);

	// populate
	$html = file_get_contents(SERVER_ROOT . '/app/templates/views/quote.html');
	$html = str_replace('{{ date }}', date("d/m/Y"), $html);
	$html = str_replace('{{ quote-number }}', $order->idCode, $html);
	$html = str_replace('{{ cart-net-price }}', $order->netPriceString, $html);
	$html = str_replace('{{ cart-vat }}', $order->vatString, $html);
	$html = str_replace('{{ cart-gross-price }}', $order->priceString, $html);
	$html = str_replace('{{ customer-name }}', $order->customer->name, $html);
	$html = str_replace('{{ products }}', $productsHtml, $html);

	// generate the pdf
	if (!$debug) {
		$options = new \Dompdf\Options();
		$options->set('isRemoteEnabled', true);
		$options->set('defaultFont', 'Helvetica');

		$dompdf = new \Dompdf\Dompdf($options);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream('Preventivo_' . str_replace('#', '', $order->idCode) . '.pdf');
	} else {
		exit($html);
	}
}

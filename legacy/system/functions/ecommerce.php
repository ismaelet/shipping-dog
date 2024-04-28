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
				// if the user has a cart transfer the guest orders to it
				\Legacy\System\Classes\LegacyDB::update('ecommerce_orders SET cart_id = ? WHERE cart_id = ?', [$permanentCustomer->cart->id, $guestCustomer->cart->id]);
				\Legacy\System\Classes\LegacyDB::delete('ecommerce_carts WHERE id = ?', [$guestCustomer->cart->id]);
			} else {
				// otherwise convert the session cart to the user cart
				\Legacy\System\Classes\LegacyDB::update('ecommerce_carts SET customerId=? WHERE id = ?', [$permanentCustomer->id, $guestCustomer->cart->id]);
				\Legacy\System\Classes\LegacyDB::update('ecommerce_orders SET customerId=? WHERE cart_id = ?', [$permanentCustomer->id, $guestCustomer->cart->id]);

				// add an address
				if (!$guestCustomer->cart->addressId) {
					$addressId = \Legacy\System\Classes\LegacyDB::find('id FROM ecommerce_addresses WHERE customerId=? ORDER BY id DESC', [$permanentCustomer->id]);
					\Legacy\System\Classes\LegacyDB::update('ecommerce_carts SET addressId=? WHERE id = ?', [$addressId, $guestCustomer->cart->id]);
				}
			}
		}

		$_SESSION['customerId'] = $permanentCustomerId;
	}
}

/*
* 4. Downloads
*/
function download_quote($cart_id) {
	$debug = false;
	$cart = new Cart($cart_id);

	// products
	$productsHtml = [];

	foreach ($cart->orders as $orderKey => $order) {
		$productsHtml[] = '
		<div class="product product--' . ($orderKey % 2 ? 'even' : 'odd') . '">
			<p>' . $order->product->idCode . '</p>
			<p>' . $order->product->name . '</p>
			<p>' . $order->quantity . '</p>
			<p>' . $order->netPriceString . '</p>
			<p>' . $order->vatString . '</p>
			<p>' . $order->priceString . '</p>
		</div>';
	}

	$productsHtml = implode('', $productsHtml);

	// populate
	$html = file_get_contents(SERVER_ROOT . '/app/templates/views/quote.html');
	$html = str_replace('{{ date }}', date("d/m/Y"), $html);
	$html = str_replace('{{ quote-number }}', $cart->idCode, $html);
	$html = str_replace('{{ cart-net-price }}', $cart->netPriceString, $html);
	$html = str_replace('{{ cart-vat }}', $cart->vatString, $html);
	$html = str_replace('{{ cart-gross-price }}', $cart->priceString, $html);
	$html = str_replace('{{ customer-name }}', $cart->customer->name, $html);
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
		$dompdf->stream('Preventivo_' . str_replace('#', '', $cart->idCode) . '.pdf');
	} else {
		exit($html);
	}
}

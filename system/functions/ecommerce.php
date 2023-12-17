<?php

namespace Ecommerce;

use DB;

function ecommerce_config($paramenter) {
	global $ecommerceConfig;

	if (empty($ecommerceConfig)) include_once(__DIR__ . '../../../app/config/ecommerce-config.php');

	return $ecommerceConfig[$paramenter];
}

// 1.2 Formatting
function format_price($price, $alwaysNumber = true) {
	if ($price || $alwaysNumber) {
		switch (ecommerce_config('currency')) {
			case 'BRL':
				$priceString = 'R$ ' . formatc($price);
				break;
			case 'EUR':
			default:
				$priceString = formatc($price) . ' €';
		}
	} else {
		$priceString = __('Free');
	}

	return $priceString;
}

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
function cart() {
	return cartId() ? new Cart(cartId()) : null;
}

function cartId() {
	if (logged()) {
		return DB::find('ID FROM carts WHERE buyer = ? AND status = 0', [userId()]) ?: null;
	} else {
		return $_SESSION['cartId'] ?? null;
	}
}

function associate_cart_old($permanentCustomerId, $guestCustomerId = false) {
	global $customer;

	if (!empty($customer->id)) {
		$permanentCustomer = new Customer($permanentCustomerId);
		$guestCustomer = $guestCustomerId ? new Customer($guestCustomerId) : $customer;

		if (!$permanentCustomer->id) {
			DB::add('ecommerce_customers (userId)', [$permanentCustomerId]);
			$permanentCustomer = new Customer($permanentCustomerId);
		}

		if ($guestCustomer->hasCart) {
			if ($permanentCustomer->hasCart) {
				// if the user has a cart transfer the guest orders to it
				DB::update('ecommerce_orders SET cartId=? WHERE cartId=?', [$permanentCustomer->cart->id, $guestCustomer->cart->id]);
				DB::delete('ecommerce_carts WHERE id=?', [$guestCustomer->cart->id]);
			} else {
				// otherwise convert the session cart to the user cart
				DB::update('ecommerce_carts SET customerId=? WHERE id=?', [$permanentCustomer->id, $guestCustomer->cart->id]);
				DB::update('ecommerce_orders SET customerId=? WHERE cartId=?', [$permanentCustomer->id, $guestCustomer->cart->id]);

				// add an address
				if (!$guestCustomer->cart->addressId) {
					$addressId = DB::find('id FROM ecommerce_addresses WHERE customerId=? ORDER BY id DESC', [$permanentCustomer->id]);
					DB::update('ecommerce_carts SET addressId=? WHERE id=?', [$addressId, $guestCustomer->cart->id]);
				}
			}
		}

		$_SESSION['customerId'] = $permanentCustomerId;
	}
}

function order_count() {
	return cart()->quantity;
}

/*
* 4. Downloads
*/
function download_quote($cartId) {
	$debug = false;
	$cart = new Cart($cartId);

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

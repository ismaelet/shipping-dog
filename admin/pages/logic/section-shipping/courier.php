<?php

use App\Models\Ecommerce\Address;
use App\Models\Ecommerce\Cart;
use Ecommerce\ShippingMethod;
use Shuchkin\SimpleXLSXGen as XLS;

/*
* Page Configuration
*/

$pageConfig['free'] = true;

$carts = Cart::where('status', 1)->where('shipID', '!=', ShippingMethod::NO_DELIVERY_SHIPPING_METHOD_ID)->orderBy('time', 'asc');

if ($carts->count()) {
	$firstCart = $carts->first();
	$lastCart = Cart::where('status', 1)->where('shipID', '!=', ShippingMethod::NO_DELIVERY_SHIPPING_METHOD_ID)->orderBy('time', 'desc')->first();
} else {
	$lastCart = Cart::where('status', '>', 0)->where('shipID', '!=', ShippingMethod::NO_DELIVERY_SHIPPING_METHOD_ID)->orderBy('time', 'desc')->first();
}

// submit
if (isset($_POST['submit'])) {
	$rows = [];

	$firstSelectedCartId = $_POST['first_cart_id'];
	$lastSelectedCartId = $_POST['last_cart_id'];
	$firstSelectedCart = Cart::find($firstSelectedCartId);
	$lastSelectedCart = Cart::find($lastSelectedCartId);

	if ($firstSelectedCart && $lastSelectedCart) {

		if ((isset($firstCart) && $firstSelectedCart->ID == $firstCart->ID) && (isset($lastCart) && $lastSelectedCart->ID == $lastCart->ID)) {
			$addresses = Address::join('carts', 'addresses.cartID', '=', 'carts.ID')
				->where('carts.status', 1)
				->where('shipID', '!=', ShippingMethod::NO_DELIVERY_SHIPPING_METHOD_ID)
				->whereBetween('carts.time', [$firstSelectedCart->time, $lastSelectedCart->time])
				->orderBy('carts.time', 'asc')
				->get();
		} else {
			$addresses = Address::join('carts', 'addresses.cartID', '=', 'carts.ID')
				->where('carts.status', '>', 0)
				->where('shipID', '!=', ShippingMethod::NO_DELIVERY_SHIPPING_METHOD_ID)
				->whereBetween('carts.time', [$firstSelectedCart->time, $lastSelectedCart->time])
				->orderBy('carts.time', 'asc')
				->get();
		}

		foreach ($addresses as $address) {
			$rows[] = [
				$address->aname,
				$address->address . ', ' . $address->number,
				$address->city,
				$address->zip,
				$address->region,
				$address->email,
				'Tel: ' . $address->phone,
				1,
				1,
				$address->exchange ? 24 : ''
			];
		}

		$xlsx = XLS::fromArray($rows);
		$xlsx->downloadAs('Etichette-Corriere.xlsx');
	}
}

if (isset($_POST['process-all'])) {
	Cart::where('status', 1)->where('shipID', '!=', ShippingMethod::NO_DELIVERY_SHIPPING_METHOD_ID)->update(['status' => 2]);

	redirect();
}

<?php

use App\Models\Ecommerce\Address as EcommerceAddress;
use App\Models\Ecommerce\Order as EcommerceOrder;
use App\Models\Ecommerce\ShippingMethod as EcommerceShippingMethod;
use Legacy\Classes\Ecommerce\Coupon;

/**
 * TEMP
 */
// $ordersToFix = EcommerceOrder::where('id', '>=', 92026)
// 	->get();


// foreach ($ordersToFix as $order) {
// 	$order->productGain = $order->product->sellerGain();
// 	$order->save();
// }

// setup variables
$loc = \Legacy\System\Classes\LegacyView::get('step');
$error = $_GET['error'] ?? false;

// address
$address = EcommerceAddress::where('cart_id', cart()->id)->first();

if (!$address && auth()->check()) {
	$address = EcommerceAddress::where('user_id', auth()->id())->latest()->first();
}

// setup cart
$countries = \Legacy\System\Classes\LegacyDB::select('* FROM sys_country WHERE hidden = 0 ORDER BY name ASC');

// setup warnings
$warning = warning_banner();

/*
* Shipping methods
*/
$shippingZones = \Legacy\System\Classes\LegacyDB::find('zone FROM sys_country WHERE id = ?', [cart_old()->countryId]);

if (!in_array(cart()->shipping_method_id, EcommerceShippingMethod::FREE_SHIPPING_METHODS)) {
	// if the shipping isn't free, fetch the shipping methods
	$shippingMethods =  EcommerceShippingMethod::whereRaw('FIND_IN_SET(tag, "' . $shippingZones . '") > 0')
		->orderByDesc('tier')
		->get();
} else {
	// if the shipping is free, fetch only the free shipping method
	$shippingMethods = EcommerceShippingMethod::where('id', EcommerceShippingMethod::FREE_SHIPPING_METHODS[0])->get();
}

/**
 * Save the address
 */
if (!empty($_POST)) {
	$full_name = str_replace('-', ',', $_POST['full_name']);
	$addr = ucwords(strtolower(trim($_POST['address'])));
	$num = trim($_POST['number']);
	$zip = strtoupper(trim($_POST['zip']));
	$city = str_replace('-', ',', $_POST['city']);
	$city = ucwords(strtolower(trim($city)));
	$reg = strtoupper(trim($_POST['region']));
	$email = strtolower(trim($_POST['email']));
	$phone = trim($_POST['phone']);
	$security = random_string();

	EcommerceAddress::updateOrCreate([
		'cart_id' => cart()->id,
	], [
		'user_id' => auth()->id() ?: null,
		'full_name' => $full_name,
		'address' => $addr,
		'number' => $num,
		'zip' => $zip,
		'city' => $city,
		'region' => $reg,
		'email' => $email,
		'phone' => $phone,
		'security' => $security
	]);
}

// remove item
if (isset($_POST['remove'])) {
	$order = EcommerceOrder::find($_POST['remove']);

	if ($order) {
		$order->delete();
	}

	redirect_old();
}

// add code
if (isset($_POST['add_code'])) {
	$coupon = Coupon::generateFromCode($_POST['code']);
	$error = cart_old()->addCoupon($coupon->id);

	if (!$error) {
		redirect_old('#code-anch');
	} else {
		redirect_old('?step-id=2&error=' . $error . '#code-anch');
	}
}

// remove code
if (isset($_POST['del_code'])) {
	cart_old()->removeCoupon();

	redirect_old('#code-anch');
}

if (isset($_POST['item_credit'])) {
	\Legacy\System\Classes\LegacyDB::update('orders SET credit=1 WHERE id = ?', [$_POST['item_credit']]);

	cart_old()->update();

	redirect_old('#items');
}

if (isset($_POST['item_no_credit'])) {
	\Legacy\System\Classes\LegacyDB::update('orders SET credit=0 WHERE id = ?', [$_POST['item_no_credit']]);

	cart_old()->update();

	redirect_old('#items');
}

if (isset($_POST['ship_credit'])) {
	cart_old()->updateShippingMethodId(1);
	cart_old()->updateShippingWithCredit(true);

	redirect_old('#shipping');
}

if (isset($_POST['ship_no_credit'])) {
	cart_old()->updateShippingMethodId(2);
	cart_old()->updateShippingWithCredit(false);

	redirect_old('#shipping');
}

/**
 * Advance to next step
 */
if (isset($_POST['submit'])) {
	cart_old()->advanceAndredirect_old();
}

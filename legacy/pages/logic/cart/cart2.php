<?php

use App\Models\Ecommerce\Address;
use App\Models\Ecommerce\OrderItem;
use App\Models\Ecommerce\ShippingMethod;
use Legacy\Classes\Ecommerce\Coupon;
use Legacy\Classes\Ecommerce\Order as LegacyOrder;

$cart = cart();

$legacyCart = new LegacyOrder($cart->id);

// setup variables
$loc = \Legacy\System\Classes\LegacyView::get('step');
$error = $_GET['error'] ?? false;

// address
$address = Address::where('order_id', $cart->id)->first();

if (!$address && auth()->check()) {
	$address = Address::where('user_id', auth()->id())->latest()->first();
}

// setup warnings
$warning = warning_banner();

/*
* Shipping methods
*/
if (!in_array($cart->shipping_method_id, ShippingMethod::FREE_SHIPPING_METHODS)) {
	// if the shipping isn't free, fetch the shipping methods
	$shippingMethods = ShippingMethod::whereRaw('FIND_IN_SET(tag, "sta,pre,rit") > 0')
		->orderByDesc('tier')
		->get();
} else {
	// if the shipping is free, fetch only the free shipping method
	$shippingMethods = ShippingMethod::where('id', ShippingMethod::FREE_SHIPPING_METHODS[0])->get();
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

	if (
		!$full_name || !$email || !$phone ||
		($cart->shipping_method_id != 11 && (!$addr || !$num || !$zip || !$city || !$reg))
	) {
		redirect_old('?error=address');
	}

	Address::updateOrCreate([
		'order_id' => $cart->id,
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
	$orderItem = OrderItem::find($_POST['remove']);

	if ($orderItem) {
		$orderItem->delete();
	}

	redirect_old();
}

// add code
if (isset($_POST['add_code'])) {
	$coupon = Coupon::generateFromCode($_POST['code']);
	$error = $legacyCart->addCoupon($coupon->id);

	if (!$error) {
		redirect_old('#code-anch');
	} else {
		redirect_old('?step-id=2&error=' . $error . '#code-anch');
	}
}

// remove code
if (isset($_POST['del_code'])) {
	$legacyCart->removeCoupon();

	redirect_old('#code-anch');
}

if (isset($_POST['item_credit'])) {
	\Legacy\System\Classes\LegacyDB::update('order_items SET credit=1 WHERE id = ?', [$_POST['item_credit']]);

	$legacyCart->update();

	redirect_old('#items');
}

if (isset($_POST['item_no_credit'])) {
	\Legacy\System\Classes\LegacyDB::update('order_items SET credit=0 WHERE id = ?', [$_POST['item_no_credit']]);

	$legacyCart->update();

	redirect_old('#items');
}

if (isset($_POST['ship_credit'])) {
	$legacyCart->updateShippingMethodId(1);
	$legacyCart->updateShippingWithCredit(true);

	redirect_old('#shipping');
}

if (isset($_POST['ship_no_credit'])) {
	$legacyCart->updateShippingMethodId(2);
	$legacyCart->updateShippingWithCredit(false);

	redirect_old('#shipping');
}

/**
 * Advance to next step
 */
if (isset($_POST['submit'])) {
	$cart->shipping_method_id = request()->shipping_method_id;
	$cart->save();
	$cart->updatePrice();

	$legacyCart->advanceAndredirect_old();
}

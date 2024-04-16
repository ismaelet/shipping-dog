<?php

use Ecommerce\Address;
use Ecommerce\Cart;
use Ecommerce\Coupon;
use Ecommerce\Order;
use Ecommerce\ShippingMethod;


use function Ecommerce\cart_id;
use function Ecommerce\customerId;

// setup variables
$loc = ViewOld::get('step');
$error = $_GET['error'] ?? false;

// address
$addressId = DBOld::find('id FROM ' . Address::TABLE . ' WHERE ' . Address::CART_INDEX . ' = ?', [\App\Helpers\App::cartOld()->id]);

if (!$addressId && auth()->id()) {
	$addressId = DBOld::find('id FROM ' . Address::TABLE . ' WHERE ' . Address::CUSTOMER_INDEX . ' = ?', [auth()->id()]);
}

if (!$addressId) {
	$addressId = DBOld::add(Address::TABLE . ' (cart_id) VALUES (?)', [\App\Helpers\App::cartOld()->id]);
}

$address = new Address($addressId);

// setup cart
$countries = DBOld::select('* FROM sys_country WHERE hidden = 0 ORDER BY name ASC');

// setup warnings
$warning = warning_banner();

/*
* Shipping methods
*/
$shippingZones = DBOld::find('zone FROM sys_country WHERE id = ?', [\App\Helpers\App::cartOld()->countryId]);

if (\App\Helpers\App::cartOld()->shippingMethodId != 1) {
	// if the shipping isn't free, fetch the shipping methods
	$shippingMethodRes = DBOld::select('id FROM sys_shipping WHERE FIND_IN_SET(tag, ?) > 0 ORDER BY tier DESC', [$shippingZones]);
} else {
	// if the shipping is free, fetch only the free shipping method
	$shippingMethodRes = DBOld::select('id FROM sys_shipping WHERE id = 1');
}

$shippingMethods = rows_to_objects($shippingMethodRes, ShippingMethod::class);

// checkout


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

	DBOld::put(Address::TABLE, [
		'cart_id' => \App\Helpers\App::cartOld()->id,
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
	], \App\Helpers\App::cartOld()->addressId);
}

// remove item
if (isset($_POST['remove'])) {
	(new Order($_POST['remove']))->delete();

	redirect_old();
}

// add code
if (isset($_POST['add_code'])) {
	$coupon = Coupon::generateFromCode($_POST['code']);
	$error = \App\Helpers\App::cartOld()->addCoupon($coupon->id);

	if (!$error) {
		redirect_old('#code-anch');
	} else {
		redirect_old('?step-id=2&error=' . $error . '#code-anch');
	}
}

// remove code
if (isset($_POST['del_code'])) {
	\App\Helpers\App::cartOld()->removeCoupon();

	redirect_old('#code-anch');
}

if (isset($_POST['item_credit'])) {
	DBOld::update('orders SET credit=1 WHERE id = ?', [$_POST['item_credit']]);

	\App\Helpers\App::cartOld()->update();

	redirect_old('#items');
}

if (isset($_POST['item_no_credit'])) {
	DBOld::update('orders SET credit=0 WHERE id = ?', [$_POST['item_no_credit']]);

	\App\Helpers\App::cartOld()->update();

	redirect_old('#items');
}

if (isset($_POST['ship_credit'])) {
	\App\Helpers\App::cartOld()->updateShippingMethodId(1);
	\App\Helpers\App::cartOld()->updateShippingWithCredit(true);

	redirect_old('#shipping');
}

if (isset($_POST['ship_no_credit'])) {
	\App\Helpers\App::cartOld()->updateShippingMethodId(2);
	\App\Helpers\App::cartOld()->updateShippingWithCredit(false);

	redirect_old('#shipping');
}

/**
 * Advance to next step
 */
if (isset($_POST['submit'])) {
	\App\Helpers\App::cartOld()->advanceAndredirect_old();
}

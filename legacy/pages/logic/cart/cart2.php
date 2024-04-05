<?php

use Ecommerce\Address;
use Ecommerce\Cart;
use Ecommerce\Coupon;
use Ecommerce\Order;
use Ecommerce\ShippingMethod;


use function Ecommerce\cartId;
use function Ecommerce\customerId;

// setup variables
$loc = ViewOld::get('step');
$error = $_GET['error'] ?? false;

// address
$addressId = DBOld::find('ID FROM ' . Address::TABLE . ' WHERE ' . Address::CART_INDEX . ' = ?', [cart_old()->id]);

if (!$addressId && auth()->id()) {
	$addressId = DBOld::find('ID FROM ' . Address::TABLE . ' WHERE ' . Address::CUSTOMER_INDEX . ' = ?', [auth()->id()]);
}

if (!$addressId) {
	$addressId = DBOld::add(Address::TABLE . ' (cartID) VALUES (?)', [cart_old()->id]);
}

$address = new Address($addressId);

// setup cart
$countries = DBOld::select('* FROM sys_country WHERE hidden = 0 ORDER BY name_it ASC');

// setup warnings
$warning = warning_banner();

/*
* Shipping methods
*/
$shippingZones = DBOld::find('zone FROM sys_country WHERE ID = ?', [cart_old()->countryId]);

if (cart_old()->shippingMethodId != 1) {
	// if the shipping isn't free, fetch the shipping methods
	$shippingMethodRes = DBOld::select('ID FROM sys_shipping WHERE FIND_IN_SET(tag, ?) > 0 ORDER BY tier DESC', [$shippingZones]);
} else {
	// if the shipping is free, fetch only the free shipping method
	$shippingMethodRes = DBOld::select('ID FROM sys_shipping WHERE ID = 1');
}

$shippingMethods = rows_to_objects($shippingMethodRes, ShippingMethod::class);

// checkout


/**
 * Save the address
 */
if (!empty($_POST)) {
	$aname = str_replace('-', ',', $_POST['aname']);
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
		'cartID' => cart_old()->id,
		'buyer' => auth()->id() ?: null,
		'aname' => $aname,
		'address' => $addr,
		'number' => $num,
		'zip' => $zip,
		'city' => $city,
		'region' => $reg,
		'email' => $email,
		'phone' => $phone,
		'security' => $security
	], cart_old()->addressId);
}

// remove item
if (isset($_POST['remove'])) {
	(new Order($_POST['remove']))->delete();

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
	DBOld::update('orders SET credit=1 WHERE ID = ?', [$_POST['item_credit']]);

	cart_old()->update();

	redirect_old('#items');
}

if (isset($_POST['item_no_credit'])) {
	DBOld::update('orders SET credit=0 WHERE ID = ?', [$_POST['item_no_credit']]);

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

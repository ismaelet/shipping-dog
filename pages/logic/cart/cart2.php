<?php

use Ecommerce\Address;
use Ecommerce\Cart;
use Ecommerce\Coupon;
use Ecommerce\Order;
use Ecommerce\ShippingMethod;

use function Ecommerce\cart;
use function Ecommerce\cartId;
use function Ecommerce\customerId;

// setup variables
$loc = View::get('step');
$error = $_GET['error'] ?? false;

// address
$addressId = DB::find('ID FROM ' . Address::TABLE . ' WHERE ' . Address::CART_INDEX . ' = ?', [cartId()]);

if (!$addressId && userId()) {
	$addressId = DB::find('ID FROM ' . Address::TABLE . ' WHERE ' . Address::CUSTOMER_INDEX . ' = ?', [userId()]);
}

if (!$addressId) {
	$addressId = DB::add(Address::TABLE . ' (cartID) VALUES (?)', [cartId()]);
}

$address = new Address($addressId);

// setup cart
$countries = DB::select('* FROM sys_country WHERE hidden = 0 ORDER BY name_it ASC');

// setup warnings
$warning = warning_banner();

/*
* Shipping methods
*/
$shippingZones = DB::find('zone FROM sys_country WHERE ID = ?', [cart()->countryId]);

if (cart()->shippingMethodId != 1) {
	// if the shipping isn't free, fetch the shipping methods
	$shippingMethodRes = DB::select('ID FROM sys_shipping WHERE FIND_IN_SET(tag, ?) > 0 ORDER BY tier DESC', [$shippingZones]);
} else {
	// if the shipping is free, fetch only the free shipping method
	$shippingMethodRes = DB::select('ID FROM sys_shipping WHERE ID = 1');
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

	DB::put(Address::TABLE, [
		'cartID' => cartId(),
		'buyer' => userId() ?: null,
		'aname' => $aname,
		'address' => $addr,
		'number' => $num,
		'zip' => $zip,
		'city' => $city,
		'region' => $reg,
		'email' => $email,
		'phone' => $phone,
		'security' => $security
	], cart()->addressId);
}

// remove item
if (isset($_POST['remove'])) {
	(new Order($_POST['remove']))->delete();

	redirect();
}

// add code
if (isset($_POST['add_code'])) {
	$coupon = Coupon::generateFromCode($_POST['code']);
	$error = cart()->addCoupon($coupon->id);

	if (!$error) {
		redirect('#code-anch');
	} else {
		redirect('?step-id=2&error=' . $error . '#code-anch');
	}
}

// remove code
if (isset($_POST['del_code'])) {
	cart()->removeCoupon();

	redirect('#code-anch');
}

if (isset($_POST['item_credit'])) {
	DB::update('orders SET credit=1 WHERE ID = ?', [$_POST['item_credit']]);

	cart()->update();

	redirect('#items');
}

if (isset($_POST['item_no_credit'])) {
	DB::update('orders SET credit=0 WHERE ID = ?', [$_POST['item_no_credit']]);

	cart()->update();

	redirect('#items');
}

if (isset($_POST['ship_credit'])) {
	cart()->updateShippingMethodId(1);
	cart()->updateShippingWithCredit(true);

	redirect('#shipping');
}

if (isset($_POST['ship_no_credit'])) {
	cart()->updateShippingMethodId(2);
	cart()->updateShippingWithCredit(false);

	redirect('#shipping');
}

/**
 * Advance to next step
 */
if (isset($_POST['submit'])) {
	cart()->advanceAndRedirect();
}

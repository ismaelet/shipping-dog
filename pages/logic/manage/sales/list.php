<?php

/* TEMP */

use Ecommerce\Cart;
use Ecommerce\Seller;

// set earned
// $tempSellers = DB::select('userID FROM stores WHERE userID IN (SELECT sellerId FROM orders)');

// while ($tempSellerRow = $tempSellers->fetch_assoc()) {
// 	$tempSeller = new Seller($tempSellerRow['userID']);

// 	DB::update('stores SET earned = ? WHERE userID = ?', [$tempSeller->earned, $tempSeller->id]);
// }

// set credit
DB::update('stores SET credit = earned * 0.1');

// remove used credit
$tempCarts = DB::select('ID FROM carts WHERE status > 0 AND (credit_ship = 1 OR ID IN (SELECT cartID FROM orders WHERE credit = 1))');

while ($tempCartRow = $tempCarts->fetch_assoc()) {
	$tempCart = new Cart($tempCartRow['ID']);

	DB::update('stores SET credit = credit - ? WHERE userID = ?', [$tempCart->usedCredit, $tempCart->customerId]);
}
/* END TEMP */

$from = 0;
$j = 1;
$block = 200;
$tot = 0;
$old = '';

$sort = $_GET['sort'] ?? 'time';
$sort = !in_array($sort, ['time', 'designs']) ? 'time' : $sort;
$year = isset($_GET['year']) ? $_GET['year'] : null;

$saleN = DB::count('orders WHERE itemID IN (SELECT ID FROM items WHERE userID = ?) AND cartID IN (SELECT ID FROM carts WHERE status > 0)', [userId()]);

// send payment request
if (isset($_POST['request-payment'])) {
	redirect('/manage/payments/request');
}

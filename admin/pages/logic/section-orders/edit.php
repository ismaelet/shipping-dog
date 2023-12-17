<?php

use Ecommerce\Cart;

global $id;
global $cart;

$id = $_GET['id'] ?? false;
$updated = $_GET['updated'] ?? false;
$added = false;

/*
* Setup cart
*/
$cart = new Cart($id);
View::$title = 'Ordine ' . $cart->idCode;

/*
* Form events
*/
if (isset($_POST['change-status'])) {
	$status = $_POST['change-status'];
	$comment = $_POST['comment'] ?? false;

	if ($status == 'received') {
		$cart->confirm(false);
	} else {
		$cart->updateStatus($status, false);
	}

	if ($comment) {
		if (DB::exists('ecommerce_carts_comments WHERE cartId=?', $id)) {
			DB::update('ecommerce_carts_comments SET comment=? WHERE cartId=?', [$comment, $id]);
		} else {
			DB::add('ecommerce_carts_comments (cartId, comment) VALUES (?, ?)', [$id, $comment]);
		}
	}

	redirect('?id=' . $cart->id . '&updated=1');
}

if (isset($_POST['update-tracking'])) {
	$cart->updateTracking($_POST['tracking']);
	redirect('?id=' . $cart->id . '&updated=1');
}

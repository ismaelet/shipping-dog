<?php

use Legacy\Classes\Ecommerce\Cart;

global $id;
global $cart;

$id = $_GET['id'] ?? false;
$updated = $_GET['updated'] ?? false;
$added = false;

/*
* Setup cart
*/
$cart = new Cart($id);
\Legacy\System\Classes\LegacyView::$title = 'Ordine ' . $cart->idCode;

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

	redirect_old('?id=' . $cart->id . '&updated=1');
}

if (isset($_POST['update-tracking'])) {
	$cart->updateTracking($_POST['tracking']);
	redirect_old('?id=' . $cart->id . '&updated=1');
}

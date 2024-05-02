<?php

use Legacy\Classes\Ecommerce\OrderItem;

global $id;
global $order;

$id = $_GET['id'] ?? false;
$updated = $_GET['updated'] ?? false;
$added = false;

/*
* Setup cart
*/
$order = new Cart($id);
\Legacy\System\Classes\LegacyView::$title = 'Ordine ' . $order->idCode;

/*
* Form events
*/
if (isset($_POST['change-status'])) {
	$status = $_POST['change-status'];
	$comment = $_POST['comment'] ?? false;

	if ($status == 'received') {
		$order->confirm(false);
	} else {
		$order->updateStatus($status, false);
	}

	redirect_old('?id=' . $order->id . '&updated=1');
}

if (isset($_POST['update-tracking'])) {
	$order->updateTracking($_POST['tracking']);
	redirect_old('?id=' . $order->id . '&updated=1');
}

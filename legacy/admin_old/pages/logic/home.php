<?php

use App\Models\Ecommerce\Seller as EcommerceSeller;
use Ecommerce\Seller;

// accept seller entry
if (isset($_POST['accept-seller'])) {
	(new Seller($_POST['accept-seller']))->accept();
}

// delete seller entry
if (isset($_POST['delete-seller'])) {
	$seller = EcommerceSeller::find($_POST['delete-seller']);
	$seller->deleteAndClean();
}

// select pending lables
$lblN = DBOld::count('carts WHERE status=1 && (shipID=2 OR shipID=1)');

// select page groups
$categories = DBOld::select('* FROM admin_categories ORDER BY ID ASC');

// select shops awaiting for approval
$sellers = DBOld::select('userID, name FROM stores WHERE status = 0 AND userID IN (SELECT userID FROM designs WHERE status > 0) AND userID NOT IN (2) ORDER BY userID DESC');

// select balace
$balance['gained'] = DBOld::sum('total FROM stores');
$balance['payed'] = DBOld::sum('value FROM stores_payments WHERE other=0');
$balance['extra'] = DBOld::sum('value FROM stores_payments WHERE other=1');

$balance['tot'] = $balance['gained'] + $balance['extra'];
$balance['topay'] = $balance['tot'] - $balance['payed'];

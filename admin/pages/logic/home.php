<?php

use Ecommerce\Seller;

// accept seller entry
if (isset($_POST['accept-seller'])) {
	(new Seller($_POST['accept-seller']))->accept();
}

// delete seller entry
if (isset($_POST['delete-seller'])) {
	(new Seller($_POST['delete-seller']))->delete();
}

// select pending lables
$lblN = DB::count('carts WHERE status=1 && (shipID=2 OR shipID=1)');

// select page groups
$categories = DB::select('* FROM admin_categories ORDER BY ID ASC');

// select shops awaiting for approval
$sellers = DB::select('userID, name FROM stores WHERE status=0 AND userID IN (SELECT userID FROM designs WHERE status>0) AND userID NOT IN (4855) ORDER BY userID DESC');

// select balace
$balance['gained'] = DB::sum('total FROM stores');
$balance['payed'] = DB::sum('value FROM stores_payments WHERE other=0');
$balance['extra'] = DB::sum('value FROM stores_payments WHERE other=1');

$balance['tot'] = $balance['gained'] + $balance['extra'];
$balance['topay'] = $balance['tot'] - $balance['payed'];

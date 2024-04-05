<?php

$updated = false;

/*
* Page configuration
*/
$pageConfig['free'] = true;
$pageConfig['centered'] = true;

if (isset($_POST['update'])) {
	$updateType = $_POST['update'];

	DBOld::update('stores SET sales=0, rsales=0');
	DBOld::update('designs SET sales=0, rsales=0');
	DBOld::update('items SET sales=0, rsales=0');

	/*
	* Recent sales
	*/
	DBOld::update('
		stores
		INNER JOIN (
			SELECT sellerId, SUM(orders.quantity) as saleQuantity
			FROM orders
			INNER JOIN carts ON carts.ID = orders.cartID
			WHERE carts.status > 0 AND carts.time > DATE_SUB(NOW(), INTERVAL 1 YEAR)
			GROUP BY sellerId
		) as recentOrders
		ON stores.userID = recentOrders.sellerId
		SET rsales = saleQuantity
	');

	DBOld::update('
		designs
		INNER JOIN (
			SELECT designId, SUM(orders.quantity) as saleQuantity
			FROM orders
			INNER JOIN carts ON carts.ID = orders.cartID
			WHERE carts.status > 0 AND carts.time > DATE_SUB(NOW(), INTERVAL 1 YEAR)
			GROUP BY designId
		) as recentOrders
		ON designs.ID = recentOrders.designId
		SET rsales = saleQuantity
	');

	DBOld::update('
		items
		INNER JOIN (
			SELECT itemID, SUM(orders.quantity) as saleQuantity
			FROM orders
			INNER JOIN carts ON carts.ID = orders.cartID
			WHERE carts.status > 0 AND carts.time > DATE_SUB(NOW(), INTERVAL 1 YEAR)
			GROUP BY itemID
		) as recentOrders
		ON items.ID = recentOrders.itemID
		SET rsales = saleQuantity
	');

	/*
	* Total sales
	*/
	DBOld::update('
		stores
		INNER JOIN (
			SELECT sellerId, SUM(orders.quantity) as saleQuantity
			FROM orders
			INNER JOIN carts ON carts.ID = orders.cartID
			WHERE carts.status > 0
			GROUP BY sellerId
		) as recentOrders
		ON stores.userID = recentOrders.sellerId
		SET sales = saleQuantity
	');

	DBOld::update('
		designs
		INNER JOIN (
			SELECT designId, SUM(orders.quantity) as saleQuantity
			FROM orders
			INNER JOIN carts ON carts.ID = orders.cartID
			WHERE carts.status > 0
			GROUP BY designId
		) as recentOrders
		ON designs.ID = recentOrders.designId
		SET sales = saleQuantity
	');

	DBOld::update('
		items
		INNER JOIN (
			SELECT itemID, SUM(orders.quantity) as saleQuantity
			FROM orders
			INNER JOIN carts ON carts.ID = orders.cartID
			WHERE carts.status > 0
			GROUP BY itemID
		) as recentOrders
		ON items.ID = recentOrders.itemID
		SET sales = saleQuantity
	');

	$updated = true;
}

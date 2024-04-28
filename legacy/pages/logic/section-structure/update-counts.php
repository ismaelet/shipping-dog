<?php

$updated = false;

/*
* Page configuration
*/
$pageConfig['free'] = true;
$pageConfig['centered'] = true;

if (isset($_POST['update'])) {
	$updateType = $_POST['update'];

	\Legacy\System\Classes\LegacyDB::update('stores SET sales=0, recent_sales=0');
	\Legacy\System\Classes\LegacyDB::update('designs SET sales=0, recent_sales=0');
	\Legacy\System\Classes\LegacyDB::update('items SET sales=0, recent_sales=0');

	/*
	* Recent sales
	*/
	\Legacy\System\Classes\LegacyDB::update('
		stores
		INNER JOIN (
			SELECT seller_id, SUM(orders.quantity) as saleQuantity
			FROM orders
			INNER JOIN carts ON carts.id = orders.cart_id
			WHERE carts.status > 0 AND carts.time > DATE_SUB(NOW(), INTERVAL 1 YEAR)
			GROUP BY seller_id
		) as recentOrders
		ON stores.user_id = recentOrders.seller_id
		SET recent_sales = saleQuantity
	');

	\Legacy\System\Classes\LegacyDB::update('
		designs
		INNER JOIN (
			SELECT design_id, SUM(orders.quantity) as saleQuantity
			FROM orders
			INNER JOIN carts ON carts.id = orders.cart_id
			WHERE carts.status > 0 AND carts.time > DATE_SUB(NOW(), INTERVAL 1 YEAR)
			GROUP BY design_id
		) as recentOrders
		ON designs.id = recentOrders.design_id
		SET recent_sales = saleQuantity
	');

	\Legacy\System\Classes\LegacyDB::update('
		items
		INNER JOIN (
			SELECT product_id, SUM(orders.quantity) as saleQuantity
			FROM orders
			INNER JOIN carts ON carts.id = orders.cart_id
			WHERE carts.status > 0 AND carts.time > DATE_SUB(NOW(), INTERVAL 1 YEAR)
			GROUP BY product_id
		) as recentOrders
		ON items.id = recentOrders.product_id
		SET recent_sales = saleQuantity
	');

	/*
	* Total sales
	*/
	\Legacy\System\Classes\LegacyDB::update('
		stores
		INNER JOIN (
			SELECT seller_id, SUM(orders.quantity) as saleQuantity
			FROM orders
			INNER JOIN carts ON carts.id = orders.cart_id
			WHERE carts.status > 0
			GROUP BY seller_id
		) as recentOrders
		ON stores.user_id = recentOrders.seller_id
		SET sales = saleQuantity
	');

	\Legacy\System\Classes\LegacyDB::update('
		designs
		INNER JOIN (
			SELECT design_id, SUM(orders.quantity) as saleQuantity
			FROM orders
			INNER JOIN carts ON carts.id = orders.cart_id
			WHERE carts.status > 0
			GROUP BY design_id
		) as recentOrders
		ON designs.id = recentOrders.design_id
		SET sales = saleQuantity
	');

	\Legacy\System\Classes\LegacyDB::update('
		items
		INNER JOIN (
			SELECT product_id, SUM(orders.quantity) as saleQuantity
			FROM orders
			INNER JOIN carts ON carts.id = orders.cart_id
			WHERE carts.status > 0
			GROUP BY product_id
		) as recentOrders
		ON items.id = recentOrders.product_id
		SET sales = saleQuantity
	');

	$updated = true;
}

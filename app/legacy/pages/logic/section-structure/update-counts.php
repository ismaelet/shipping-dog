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
	\Legacy\System\Classes\LegacyDB::update('products SET sales=0, recent_sales=0');

	/*
	* Recent sales
	*/
	\Legacy\System\Classes\LegacyDB::update('
		stores
		INNER JOIN (
			SELECT seller_id, SUM(order_items.quantity) as saleQuantity
			FROM order_items
			INNER JOIN orders ON orders.id = order_items.order_id
			WHERE orders.status > 0 AND orders.time > DATE_SUB(NOW(), INTERVAL 1 YEAR)
			GROUP BY seller_id
		) as recentorder_items
		ON stores.user_id = recentorder_items.seller_id
		SET recent_sales = saleQuantity
	');

	\Legacy\System\Classes\LegacyDB::update('
		designs
		INNER JOIN (
			SELECT design_id, SUM(order_items.quantity) as saleQuantity
			FROM order_items
			INNER JOIN orders ON orders.id = order_items.order_id
			WHERE orders.status > 0 AND orders.time > DATE_SUB(NOW(), INTERVAL 1 YEAR)
			GROUP BY design_id
		) as recentorder_items
		ON designs.id = recentorder_items.design_id
		SET recent_sales = saleQuantity
	');

	\Legacy\System\Classes\LegacyDB::update('
		items
		INNER JOIN (
			SELECT product_id, SUM(order_items.quantity) as saleQuantity
			FROM order_items
			INNER JOIN orders ON orders.id = order_items.order_id
			WHERE orders.status > 0 AND orders.time > DATE_SUB(NOW(), INTERVAL 1 YEAR)
			GROUP BY product_id
		) as recentorder_items
		ON products.id = recentorder_items.product_id
		SET recent_sales = saleQuantity
	');

	/*
	* Total sales
	*/
	\Legacy\System\Classes\LegacyDB::update('
		stores
		INNER JOIN (
			SELECT seller_id, SUM(order_items.quantity) as saleQuantity
			FROM order_items
			INNER JOIN orders ON orders.id = order_items.order_id
			WHERE orders.status > 0
			GROUP BY seller_id
		) as recentorder_items
		ON stores.user_id = recentorder_items.seller_id
		SET sales = saleQuantity
	');

	\Legacy\System\Classes\LegacyDB::update('
		designs
		INNER JOIN (
			SELECT design_id, SUM(order_items.quantity) as saleQuantity
			FROM order_items
			INNER JOIN orders ON orders.id = order_items.order_id
			WHERE orders.status > 0
			GROUP BY design_id
		) as recentorder_items
		ON designs.id = recentorder_items.design_id
		SET sales = saleQuantity
	');

	\Legacy\System\Classes\LegacyDB::update('
		items
		INNER JOIN (
			SELECT product_id, SUM(order_items.quantity) as saleQuantity
			FROM order_items
			INNER JOIN orders ON orders.id = order_items.order_id
			WHERE orders.status > 0
			GROUP BY product_id
		) as recentorder_items
		ON products.id = recentorder_items.product_id
		SET sales = saleQuantity
	');

	$updated = true;
}

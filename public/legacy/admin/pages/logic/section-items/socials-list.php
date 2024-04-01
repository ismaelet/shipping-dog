<?php

//descriptions
include_once(PUBLIC_SERVER_ROOT . '/pages/views/item/descriptions.php');

//setup page
$free = true;

//generate list
if (isset($_POST['submit'])) {

	$rows = [];

	$products = DB::select('id FROM items WHERE status=1 AND typeID NOT IN (4, 7, 9, 13, 11) ORDER BY id DESC LIMIT 1000');

	while ($product = $products->fetch_assoc()) {

		$row = [];

		$product_obj = new Ecommerce\Product($product['id'], ['name', 'type', 'img', 'price']);

		$row['id'] = $product_obj->id;
		$row['title'] = $product_obj->name . ' - ' . $product_obj->type->name;
		$row['description'] = ${'desc_' . $product_obj->type->slug . '_it'} ?? '-';
		$row['availability'] = 'in stock';
		$row['condition'] = 'new';
		$row['price'] = $product_obj->price->price . ' EUR';
		$row['link'] = 'https://shippingdog.com/shop/item?id=' . $product_obj->id;
		$row['image_link'] = 'https://shippingdog.com' . $product_obj->img[1];
		$row['brand'] = 'Shipping Dog';
		$row['google_product_category'] = 'Clothing & Accessories > Clothing';
		$row['fb_product_category'] = 'Clothing & Accessories > Clothing';

		$rows[] = $row;
	}

	download_csv($rows, 'Lista_prodotti_' . date('Y-m-d'));
}

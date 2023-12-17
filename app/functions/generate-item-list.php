<?php

use Ecommerce\Product;
use Ecommerce\ViewManager;

function generate_item_list($res) {
	while ($row = $res->fetch_assoc()) {
		$product = new Product($row['ID']);

		echo ViewManager::renderProductCard($product);
	}
}

function generate_item_box($product) {
	echo ViewManager::renderProductCard($product);
}

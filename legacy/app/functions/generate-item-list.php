<?php

use App\Models\Ecommerce\Product;
use Ecommerce\ViewManager;

function generate_item_list($res) {
	while ($row = $res->fetch_assoc()) {
		$product = Product::find($row['id']);

		echo ViewManager::renderProductCard($product);
	}
}

function generate_item_box(Product $product): void {
	echo ViewManager::renderProductCard($product);
}

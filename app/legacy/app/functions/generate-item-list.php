<?php

use App\Models\Ecommerce\Product;
use Legacy\Classes\Ecommerce\ViewManager;

function generate_item_list($res) {
	while ($row = $res->fetch_assoc()) {
		$product = Product::find($row['id']);

		echo ViewManager::renderProductCard($product);
	}
}

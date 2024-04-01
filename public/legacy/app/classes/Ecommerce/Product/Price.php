<?php

namespace Ecommerce\Product;

use DB;

class Price {

	public $price;
	public $gain_w;
	public $gain_c;
	public $base;

	function __construct($product) {
		if ($row =
			DB::row('* FROM items_prices WHERE itemID = ?', [$product->id]) ?: (DB::row('* FROM prices_stores WHERE storeID = ? AND typeID = ?', [$product->seller->id, $product->type->id]) ?:
				DB::row('* FROM prices_default WHERE typeID = ?', [$product->type->id])
			)
		) {
			$this->price = format($row['price']);
			$this->gain_w = format($row['gain_w']);
			$this->gain_c = format($row['gain_c']);
			$this->base = format($row['price'] - $row['gain_w'] / .75);
		}
	}
}

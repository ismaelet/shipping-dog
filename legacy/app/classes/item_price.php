<?php

class Price {

	public $price;
	public $gain_w;
	public $gain_c;
	public $base;

	function __construct($product) {

		$row =
			DBOld::row('* FROM items_prices WHERE product_id = ?', [$product->id]) ?:
			DBOld::row('* FROM prices_stores WHERE seller_id = ? AND type_id = ?', [$product->seller->id, $product->type->id]);

		if ($row) {
			$this->price = format($row['price']);
			$this->gain_w = format($row['gain_w']);
			$this->gain_c = format($row['gain_c']);
			$this->base = format($row['price'] - $row['gain_w'] / .75);
		}
	}
}

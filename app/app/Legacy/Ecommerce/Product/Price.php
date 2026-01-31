<?php

namespace App\Legacy\Ecommerce\Product;

use LegacyDB;

class Price {

    public $price;
    public $gain_w;
    public $gain_c;
    public $base;

    function __construct($product) {
        if ($row =
            DB::row('* FROM items_prices WHERE product_id = ?', [$product->id]) ?: (DB::row('* FROM prices_sellers WHERE seller_id = ? AND type_id = ?', [$product->seller->id, $product->type->id]) ?:
                DB::row('* FROM prices_default WHERE type_id = ?', [$product->type->id])
            )
        ) {
            $this->price = format($row['price']);
            $this->gain_w = format($row['gain_w']);
            $this->gain_c = format($row['gain_c']);
            $this->base = format($row['price'] - $row['gain_w'] / .75);
        }
    }
}

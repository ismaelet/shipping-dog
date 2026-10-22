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
            $this->price = number_format((float) ($row['price']), 2, '.', '');
            $this->gain_w = number_format((float) ($row['gain_w']), 2, '.', '');
            $this->gain_c = number_format((float) ($row['gain_c']), 2, '.', '');
            $this->base = number_format((float) ($row['price'] - $row['gain_w'] / .75), 2, '.', '');
        }
    }
}

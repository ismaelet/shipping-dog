<?php

use function Ecommerce\cart;

function cart_count() {
	return cart() ? cart()->quantity : 0;
}

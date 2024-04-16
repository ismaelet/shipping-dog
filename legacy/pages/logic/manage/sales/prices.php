<?php

use Ecommerce\Product\Type;

global $user;

// setup variabiles
$done = false;

// get types
$res_cloth = DBOld::select('id FROM items_types WHERE category=? AND hidden = 0 ORDER BY id ASC', ['c']);
$res_acc = DBOld::select('id FROM items_types WHERE category=? AND hidden = 0 ORDER BY id ASC', ['m']);

// UPDATE PRICES
if (isset($_POST['submit'])) {

	$res = DBOld::select('id FROM items_types WHERE hidden = 0 ORDER BY id ASC');

	while ($row = $res->fetch_assoc()) {

		$type = new Type($row['id']);

		$price = $_POST['price_' . $type->slug];

		$row_price = DBOld::row('* FROM prices_stores WHERE seller_id = ? AND type_id = ?', [auth()->id(), $type->id]) ?: DBOld::row('* FROM prices_default WHERE type_id = ?', [$type->id]);

		$price_old = $row_price['price'];
		$gain_w_old = $row_price['gain_w'];
		$gain_c_old = $row_price['gain_c'];

		$gain_w = round(($price - $price_old) * .75 + $gain_w_old, 2);
		$gain_c = round(($price - $price_old) * .75 + $gain_c_old, 2);

		// add prices
		if (!DBOld::exists('prices_stores WHERE seller_id = ? AND type_id = ?', [auth()->id(), $type->id]))
			DBOld::add('prices_stores (seller_id, type_id, price, gain_w, gain_c) VALUES (?, ?, ?, ?, ?)', [auth()->id(), $type->id, $row_price['price'], $row_price['gain_w'], $row_price['gain_c']]);

		// update prices
		DBOld::update('prices_stores SET price=?, gain_w=?, gain_c=? WHERE seller_id = ? AND type_id = ?', [$price, $gain_w, $gain_c, auth()->id(), $type->id]);
		// DBOld::update('items_prices SET price=?, gain_w=?, gain_c=? WHERE product_id IN (SELECT id FROM items WHERE user_id = ? AND type_id = ?)', [$price, $gain_w, $gain_c, auth()->id(), $type->id]);

	}

	$done = true;

	redirect_old();
}

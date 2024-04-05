<?php

use Ecommerce\Product\Type;

global $user;

// setup variabiles
$done = false;

// get types
$res_cloth = DBOld::select('ID FROM items_types WHERE category=? AND hidden = 0 ORDER BY ID ASC', ['c']);
$res_acc = DBOld::select('ID FROM items_types WHERE category=? AND hidden = 0 ORDER BY ID ASC', ['m']);

// UPDATE PRICES
if (isset($_POST['submit'])) {

	$res = DBOld::select('ID FROM items_types WHERE hidden = 0 ORDER BY ID ASC');

	while ($row = $res->fetch_assoc()) {

		$type = new Type($row['ID']);

		$price = $_POST['price_' . $type->slug];

		$row_price = DBOld::row('* FROM prices_stores WHERE storeID = ? AND typeID = ?', [auth()->id(), $type->id]) ?: DBOld::row('* FROM prices_default WHERE typeID = ?', [$type->id]);

		$price_old = $row_price['price'];
		$gain_w_old = $row_price['gain_w'];
		$gain_c_old = $row_price['gain_c'];

		$gain_w = round(($price - $price_old) * .75 + $gain_w_old, 2);
		$gain_c = round(($price - $price_old) * .75 + $gain_c_old, 2);

		// add prices
		if (!DBOld::exists('prices_stores WHERE storeID = ? AND typeID = ?', [auth()->id(), $type->id]))
			DBOld::add('prices_stores (storeID, typeID, price, gain_w, gain_c) VALUES (?, ?, ?, ?, ?)', [auth()->id(), $type->id, $row_price['price'], $row_price['gain_w'], $row_price['gain_c']]);

		// update prices
		DBOld::update('prices_stores SET price=?, gain_w=?, gain_c=? WHERE storeID = ? AND typeID = ?', [$price, $gain_w, $gain_c, auth()->id(), $type->id]);
		// DBOld::update('items_prices SET price=?, gain_w=?, gain_c=? WHERE itemID IN (SELECT ID FROM items WHERE userID = ? AND typeID = ?)', [$price, $gain_w, $gain_c, auth()->id(), $type->id]);

	}

	$done = true;

	redirect_old();
}

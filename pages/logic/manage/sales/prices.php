<?php

use Ecommerce\Product\Type;

global $user;

// setup variabiles
$done = false;

// get types
$res_cloth = DB::select('ID FROM items_types WHERE category=? AND hidden=0 ORDER BY ID ASC', ['c']);
$res_acc = DB::select('ID FROM items_types WHERE category=? AND hidden=0 ORDER BY ID ASC', ['m']);

// UPDATE PRICES
if (isset($_POST['submit'])) {

	$res = DB::select('ID FROM items_types WHERE hidden=0 ORDER BY ID ASC');

	while ($row = $res->fetch_assoc()) {

		$type = new Type($row['ID']);

		$price = $_POST['price_' . $type->slug];

		$row_price = DB::row('* FROM prices_stores WHERE storeID=? AND typeID=?', [$user->id, $type->id]) ?: DB::row('* FROM prices_default WHERE typeID=?', [$type->id]);

		$price_old = $row_price['price'];
		$gain_w_old = $row_price['gain_w'];
		$gain_c_old = $row_price['gain_c'];

		$gain_w = round(($price - $price_old) * .75 + $gain_w_old, 2);
		$gain_c = round(($price - $price_old) * .75 + $gain_c_old, 2);

		// add prices
		if (!DB::exists('prices_stores WHERE storeID=? AND typeID=?', [$user->id, $type->id]))
			DB::add('prices_stores (storeID, typeID, price, gain_w, gain_c) VALUES (?, ?, ?, ?, ?)', [$user->id, $type->id, $row_price['price'], $row_price['gain_w'], $row_price['gain_c']]);

		// update prices
		DB::update('prices_stores SET price=?, gain_w=?, gain_c=? WHERE storeID=? AND typeID=?', [$price, $gain_w, $gain_c, $user->id, $type->id]);
		// DB::update('items_prices SET price=?, gain_w=?, gain_c=? WHERE itemID IN (SELECT ID FROM items WHERE userID=? AND typeID=?)', [$price, $gain_w, $gain_c, $user->id, $type->id]);

	}

	$done = true;

	redirect();
}

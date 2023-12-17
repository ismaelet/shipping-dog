<?php

function delete_user($id) {

	delete_shop($id);

	DB::delete('users WHERE ID=?', [$id]);
}

function delete_shop($id) {

	$res = DB::select('ID FROM designs WHERE userID=?', [$id]);

	while ($row = $res->fetch_assoc()) delete_design($row['ID']);

	//delete extras
	$res = DB::select('ID FROM extras_folder WHERE storID=?', [$id]);

	while ($row = $res->fetch_assoc()) DB::delete('extras WHERE extFID=?', [$row['ID']]);

	DB::delete('extras_folder WHERE storID=?', [$id]);

	//remove followers
	DB::delete('followers WHERE followID=?', [$id]);

	//remove prices
	DB::delete('prices_stores WHERE storeID=?', [$id]);

	//delete store
	DB::delete('stores WHERE userID=?', [$id]);
}

function delete_design($id, $destroy = false) {

	$res = DB::select('ID FROM items WHERE desID=?', [$id]);

	while ($row = $res->fetch_assoc()) delete_item($row['ID']);

	//delete design
	DB::update('designs SET status=0 WHERE ID=?', [$id]);

	//delete likes
	/*DB::delete('likes WHERE desID=?', [$row['ID']]);

	//delete home pics
	DB::delete('home_pics WHERE desID=?', [$row['ID']]);

	//delete tags
	DB::delete('tags WHERE desID=?', [$row['ID']]*/

	/*unlink(SERVER_ROOT.'/uploads/designs/'.$desID.'.png');
	unlink(SERVER_ROOT.'/uploads/designs/'.$desID.'_m.png');
	//unlink(SERVER_ROOT.'/uploads/designs_originals/'.$desID.'.png');*/
}

function delete_item($id, $destroy = false) {

	if ($destroy) {

		DB::delete('items WHERE ID=?', [$id]);
		DB::delete('items_prices WHERE itemID=?'[$id]);
	} else DB::update('items SET status=0 WHERE ID=?', [$id]);
}

function delete_type($id, $permanent = false) {

	if ($permanent) {

		DB::delete('types WHERE ID=?', [$id]);
		DB::delete('items WHERE typeID=?', [$id]);
		DB::delete('prices_default WHERE typeID=?', [$id]);
	} else {

		DB::update('types SET status=0 WHERE ID=?', [$id]);
		DB::update('items SET status=0 WHERE typeID=?', [$id]);
	}
}

function delete_temp($id) {

	DB::delete('temp_users WHERE ID=?', $id);

	$cartID = DB::find('ID FROM carts WHERE buyer=? AND status=0', [$id]);

	if ($cartID) delete_cart_old($cartID);
}

function delete_cart_old($id) {

	DB::delete('carts WHERE ID=?', $id);
	DB::delete('carts_data WHERE cartID=?', $id);
	DB::delete('addresses WHERE cartID=?', $id);
}

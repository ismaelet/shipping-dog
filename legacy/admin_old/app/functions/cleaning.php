<?php

function delete_user($id) {

	delete_shop($id);

	DBOld::delete('users WHERE ID = ?', [$id]);
}

function delete_shop($id) {

	$res = DBOld::select('ID FROM designs WHERE userID = ?', [$id]);

	while ($row = $res->fetch_assoc()) delete_design($row['ID']);

	//delete extras
	$res = DBOld::select('ID FROM extras_folder WHERE storID = ?', [$id]);

	while ($row = $res->fetch_assoc()) DBOld::delete('extras WHERE extFID = ?', [$row['ID']]);

	DBOld::delete('extras_folder WHERE storID = ?', [$id]);

	//remove followers
	DBOld::delete('followers WHERE followID = ?', [$id]);

	//remove prices
	DBOld::delete('prices_stores WHERE storeID = ?', [$id]);

	//delete store
	DBOld::delete('stores WHERE userID = ?', [$id]);
}

function delete_design($id, $destroy = false) {

	$res = DBOld::select('ID FROM items WHERE desID = ?', [$id]);

	while ($row = $res->fetch_assoc()) delete_item($row['ID']);

	//delete design
	DBOld::update('designs SET status=0 WHERE ID = ?', [$id]);

	//delete likes
	/*DBOld::delete('likes WHERE desID = ?', [$row['ID']]);

	//delete home pics
	DBOld::delete('home_pics WHERE desID = ?', [$row['ID']]);

	//delete tags
	DBOld::delete('tags WHERE desID = ?', [$row['ID']]*/

	/*unlink(SERVER_ROOT.'/uploads/designs/'.$desID.'.png');
	unlink(SERVER_ROOT.'/uploads/designs/'.$desID.'_m.png');
	//unlink(SERVER_ROOT.'/uploads/designs_originals/'.$desID.'.png');*/
}

function delete_item($id, $destroy = false) {

	if ($destroy) {

		DBOld::delete('items WHERE ID = ?', [$id]);
		DBOld::delete('items_prices WHERE itemID = ?'[$id]);
	} else DBOld::update('items SET status=0 WHERE ID = ?', [$id]);
}

function delete_type($id, $permanent = false) {

	if ($permanent) {

		DBOld::delete('types WHERE ID = ?', [$id]);
		DBOld::delete('items WHERE typeID = ?', [$id]);
		DBOld::delete('prices_default WHERE typeID = ?', [$id]);
	} else {

		DBOld::update('types SET status=0 WHERE ID = ?', [$id]);
		DBOld::update('items SET status=0 WHERE typeID = ?', [$id]);
	}
}

function delete_temp($id) {

	DBOld::delete('temp_users WHERE ID = ?', $id);

	$cartID = DBOld::find('ID FROM carts WHERE buyer=? AND status=0', [$id]);

	if ($cartID) delete_cart_setup_old($cartID);
}

function delete_cart_setup_old($id) {

	DBOld::delete('carts WHERE ID = ?', $id);
	DBOld::delete('carts_data WHERE cartID = ?', $id);
	DBOld::delete('addresses WHERE cartID = ?', $id);
}

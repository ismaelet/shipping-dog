<?php

function delete_user($id) {
	\Legacy\System\Classes\LegacyDB::delete('users WHERE id = ?', [$id]);
	delete_shop($id);
}

function delete_shop($id) {

	$res = \Legacy\System\Classes\LegacyDB::select('id FROM designs WHERE user_id = ?', [$id]);

	while ($row = $res->fetch_assoc()) delete_design($row['id']);

	//delete extras
	$res = \Legacy\System\Classes\LegacyDB::select('id FROM extras_folder WHERE seller_id = ?', [$id]);

	while ($row = $res->fetch_assoc()) \Legacy\System\Classes\LegacyDB::delete('extras WHERE logo_group_id = ?', [$row['id']]);

	\Legacy\System\Classes\LegacyDB::delete('extras_folder WHERE seller_id = ?', [$id]);

	//remove followers
	\Legacy\System\Classes\LegacyDB::delete('followers WHERE follower_id = ?', [$id]);

	//remove prices
	\Legacy\System\Classes\LegacyDB::delete('prices_stores WHERE seller_id = ?', [$id]);

	//delete store
	\Legacy\System\Classes\LegacyDB::delete('stores WHERE user_id = ?', [$id]);
}

function delete_design($id, $destroy = false) {

	$res = \Legacy\System\Classes\LegacyDB::select('id FROM products WHERE design_id = ?', [$id]);

	while ($row = $res->fetch_assoc()) delete_item($row['id']);

	//delete design
	\Legacy\System\Classes\LegacyDB::update('designs SET status=0 WHERE id = ?', [$id]);

	//delete likes
	/*\Legacy\System\Classes\LegacyDB::delete('likes WHERE design_id = ?', [$row['id']]);

	//delete home pics
	\Legacy\System\Classes\LegacyDB::delete('home_pics WHERE design_id = ?', [$row['id']]);

	//delete tags
	\Legacy\System\Classes\LegacyDB::delete('tags WHERE design_id = ?', [$row['id']]*/

	/*unlink(SERVER_ROOT.'/uploads/designs/'.$design_id.'.png');
	unlink(SERVER_ROOT.'/uploads/designs/'.$design_id.'_m.png');
	//unlink(SERVER_ROOT.'/uploads/designs_originals/'.$design_id.'.png');*/
}

function delete_item($id, $permanent = false) {

	if ($permanent) {

		\Legacy\System\Classes\LegacyDB::delete('products WHERE id = ?', [$id]);
		\Legacy\System\Classes\LegacyDB::delete('items_prices WHERE product_id = ?'[$id]);
	} else \Legacy\System\Classes\LegacyDB::update('products SET status=0 WHERE id = ?', [$id]);
}

function delete_type($id, $permanent = false) {

	if ($permanent) {

		\Legacy\System\Classes\LegacyDB::delete('types WHERE id = ?', [$id]);
		\Legacy\System\Classes\LegacyDB::delete('products WHERE type_id = ?', [$id]);
		\Legacy\System\Classes\LegacyDB::delete('prices_default WHERE type_id = ?', [$id]);
	} else {

		\Legacy\System\Classes\LegacyDB::update('types SET status=0 WHERE id = ?', [$id]);
		\Legacy\System\Classes\LegacyDB::update('products SET status=0 WHERE type_id = ?', [$id]);
	}
}

function delete_temp($id) {

	\Legacy\System\Classes\LegacyDB::delete('temp_users WHERE id = ?', $id);

	$order_id = \Legacy\System\Classes\LegacyDB::find('id FROM orders WHERE user_id=? AND status=0', [$id]);

	if ($order_id) delete_cart_setup_old($order_id);
}

function delete_cart_setup_old($id) {

	\Legacy\System\Classes\LegacyDB::delete('orders WHERE id = ?', $id);
	\Legacy\System\Classes\LegacyDB::delete('addresses WHERE order_id = ?', $id);
}

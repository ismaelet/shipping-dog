<?php

function delete_user($id) {
	DBOld::delete('users WHERE id = ?', [$id]);
	delete_shop($id);
}

function delete_shop($id) {

	$res = DBOld::select('id FROM designs WHERE user_id = ?', [$id]);

	while ($row = $res->fetch_assoc()) delete_design($row['id']);

	//delete extras
	$res = DBOld::select('id FROM extras_folder WHERE seller_id = ?', [$id]);

	while ($row = $res->fetch_assoc()) DBOld::delete('extras WHERE logo_group_id = ?', [$row['id']]);

	DBOld::delete('extras_folder WHERE seller_id = ?', [$id]);

	//remove followers
	DBOld::delete('followers WHERE follower_id = ?', [$id]);

	//remove prices
	DBOld::delete('prices_stores WHERE seller_id = ?', [$id]);

	//delete store
	DBOld::delete('stores WHERE user_id = ?', [$id]);
}

function delete_design($id, $destroy = false) {

	$res = DBOld::select('id FROM items WHERE design_id = ?', [$id]);

	while ($row = $res->fetch_assoc()) delete_item($row['id']);

	//delete design
	DBOld::update('designs SET status=0 WHERE id = ?', [$id]);

	//delete likes
	/*DBOld::delete('likes WHERE design_id = ?', [$row['id']]);

	//delete home pics
	DBOld::delete('home_pics WHERE design_id = ?', [$row['id']]);

	//delete tags
	DBOld::delete('tags WHERE design_id = ?', [$row['id']]*/

	/*unlink(SERVER_ROOT.'/uploads/designs/'.$design_id.'.png');
	unlink(SERVER_ROOT.'/uploads/designs/'.$design_id.'_m.png');
	//unlink(SERVER_ROOT.'/uploads/designs_originals/'.$design_id.'.png');*/
}

function delete_item($id, $permanent = false) {

	if ($permanent) {

		DBOld::delete('items WHERE id = ?', [$id]);
		DBOld::delete('items_prices WHERE product_id = ?'[$id]);
	} else DBOld::update('items SET status=0 WHERE id = ?', [$id]);
}

function delete_type($id, $permanent = false) {

	if ($permanent) {

		DBOld::delete('types WHERE id = ?', [$id]);
		DBOld::delete('items WHERE type_id = ?', [$id]);
		DBOld::delete('prices_default WHERE type_id = ?', [$id]);
	} else {

		DBOld::update('types SET status=0 WHERE id = ?', [$id]);
		DBOld::update('items SET status=0 WHERE type_id = ?', [$id]);
	}
}

function delete_temp($id) {

	DBOld::delete('temp_users WHERE id = ?', $id);

	$cart_id = DBOld::find('id FROM carts WHERE user_id=? AND status=0', [$id]);

	if ($cart_id) delete_cart_setup_old($cart_id);
}

function delete_cart_setup_old($id) {

	DBOld::delete('carts WHERE id = ?', $id);
	DBOld::delete('addresses WHERE cart_id = ?', $id);
}

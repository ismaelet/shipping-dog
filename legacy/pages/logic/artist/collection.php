<?php

use Ecommerce\Seller;

$id = $_GET['id'] ?? 0;

// redirect if not found
if (!DBOld::exists('collections WHERE id = ?', [$id])) redirect_old('/shop');

// get collection data
$coll = new Collection($id);
$seller = new Seller($coll->seller_id, ['name', 'status', 'followed', 'theme']);

$vip = auth()->id() == $seller->id || is_admin();

// redirect if the shop isn't public
if (!$seller->status && !$vip) redirect_old('/shop');

// GET DESIGNS DATA
$limit_new = 100;

$res_items = DBOld::select('id FROM items WHERE status = 1 AND design_id IN (SELECT id FROM designs WHERE user_id = ? AND collection=?) ORDER BY sales DESC, design_id DESC, id ASC LIMIT ?', [$seller->id, $id, $limit_new]);

$res_add = DBOld::select('id FROM designs WHERE user_id = ? AND collection<>? AND status = 1 ORDER BY id DESC', [$seller->id, $id]);
$addN = $res_add->num_rows;

$res_rem = DBOld::select('id FROM designs WHERE user_id = ? AND collection=? AND status = 1 ORDER BY id DESC', [$seller->id, $id]);
$remN = $res_rem->num_rows;

// add design to the collection
if (isset($_POST['add'])) {

	$added = false;

	// add the designs
	if (isset($_POST['add-cbx'])) foreach ($_POST['add-cbx'] as $design_id) {

		DBOld::update('designs SET collection=? WHERE id = ?', [$id, $design_id]);

		$added = true;
	}

	// publish the collection if it's not empty
	if (!$coll->status && $added) DBOld::update('collections SET status=2 WHERE id = ?', [$id]);

	redirect_old();
}

// remove design
if (isset($_POST['remove'])) {

	// remove designs
	if (isset($_POST['remove-cbx'])) foreach ($_POST['remove-cbx'] as $design_id)
		DBOld::update('designs SET collection=0 WHERE id = ?', [$design_id]);

	// hide the collection if it's now empty
	if (!DBOld::exists('designs WHERE collection=?', [$id]))
		DBOld::update('collections SET status=0 WHERE id = ?', [$id]);

	redirect_old();
}

// edit collection image
if (isset($_POST['img-change'])) {

	$file = '/uploads/shops/collections/' . $id . '.jpg';
	$file_m = '/uploads/shops/collections/' . $id . '_m.jpg';

	upload_img_OLD($file, 'img', '1920');
	upload_img_OLD($file_m, 'img', '768');

	redirect_old();
}

// delete collection
if (isset($_POST['delete'])) {

	DBOld::update('designs SET collection=0 WHERE collection=?', [$id]);
	DBOld::delete('collections WHERE id = ?', [$id]);

	redirect_old('/artist?id=' . $seller->id);
}

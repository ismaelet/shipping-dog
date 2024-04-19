<?php

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Seller;

$id = $_GET['id'] ?? 0;

// redirect if not found
if (!DBOld::exists('collections WHERE id = ?', [$id])) {
	redirect_old('/shop');
}

// get collection data
$coll = new Collection($id);
$seller = Seller::where('id', $coll->seller_id)->first();
$userIsOwner = auth()->check() && auth()->id() == $seller->id;

// redirect if the shop isn't public
if (!$seller->status && !$userIsOwner) {
	redirect_old('/shop');
}

// GET DESIGNS DATA
$limit_new = 100;

$products = Product::where('status', 1)
	->where('deleted', 0)
	->whereHas('design', function ($query) use ($seller, $id) {
		$query->where('user_id', $seller->id)
			->where('collection', $id);
	})
	->orderBy('sales', 'desc')
	->orderBy('design_id', 'desc')
	->orderBy('id', 'asc')
	->limit($limit_new)
	->get();

$products = Product::where('status', 1)
	->where('deleted', 0)
	->whereHas('design', function ($query) use ($seller, $id) {
		$query->where('user_id', $seller->id)
			->where('collection', $id);
	})
	->orderBy('sales', 'desc')
	->orderBy('design_id', 'desc')
	->orderBy('id', 'asc')
	->limit($limit_new)
	->get();

$designsToAdd = Design::where('user_id', $seller->id)
	->where('collection', '!=', $id)
	->where('status', 1)
	->orderBy('id', 'desc')
	->get();

$addN = $designsToAdd->count();

$designsToRemove = Design::where('user_id', $seller->id)
	->where('collection', $id)
	->where('status', 1)
	->orderBy('id', 'desc')
	->get();

$remN = $designsToRemove->count();

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

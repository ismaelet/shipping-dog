<?php

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Design\DesignCollection;
use App\Models\Ecommerce\Product;
use Legacy\Classes\Collection;

$id = $_GET['id'] ?? 0;

// get collection data
$collection = DesignCollection::find($id);
$seller = $collection?->seller;

if (!$collection || !$seller) {
	redirect_old('/shop');
}

$coll = new Collection($id);

// redirect if the shop isn't public
$userIsOwner = auth()->check() && auth()->id() == $seller->id;

if (!$seller->status && !$userIsOwner && !is_admin()) {
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

		\Legacy\System\Classes\LegacyDB::update('designs SET collection=? WHERE id = ?', [$id, $design_id]);

		$added = true;
	}

	// publish the collection if it's not empty
	if (!$coll->status && $added) \Legacy\System\Classes\LegacyDB::update('collections SET status=2 WHERE id = ?', [$id]);

	redirect_old();
}

// remove design
if (isset($_POST['remove'])) {

	// remove designs
	if (isset($_POST['remove-cbx'])) foreach ($_POST['remove-cbx'] as $design_id)
		\Legacy\System\Classes\LegacyDB::update('designs SET collection=0 WHERE id = ?', [$design_id]);

	// hide the collection if it's now empty
	if (!\Legacy\System\Classes\LegacyDB::exists('designs WHERE collection=?', [$id]))
		\Legacy\System\Classes\LegacyDB::update('collections SET status=0 WHERE id = ?', [$id]);

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

	\Legacy\System\Classes\LegacyDB::update('designs SET collection=0 WHERE collection=?', [$id]);
	\Legacy\System\Classes\LegacyDB::delete('collections WHERE id = ?', [$id]);

	redirect_old('/artist?id=' . $seller->id);
}

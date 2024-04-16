<?php

use Ecommerce\Product;
use Ecommerce\Seller;
use Ecommerce\Shop;

// get variabiles
$id = isset($_GET['id']) ? (int) $_GET['id'] : false;

// redirect to user's shop if no ID is specified
if (!$id && App\Helpers\App::isSeller()) redirect_old('?id=' . auth()->id());

// redirect if no shop was found
if (!DBOld::exists('stores WHERE user_id = ?', [$id])) redirect_old('/shop');

// setup shop
$seller = new Seller($id, ['name', 'status', 'followed', 'theme']);

if ($seller->hasCover) ViewOld::$thumbnail = $seller->cover[0];
if ($seller->description) ViewOld::$description = $seller->description;

// setup page
ViewOld::$title = $seller->name;

// special status
$owner = auth()->id() == $id ? true : false;
$vip = $owner || is_admin() ? true : false;
$edit = isset($_GET['edit']) && $vip ? true : false;

// check if shop hasn't been approved yet
$public = $seller->status;
if (!$public && !$vip) redirect_old('/shop');

// get products data
$desMax = 10;
$collBlock = 10;
$shop_size = $id == 187 ? 10 : 20;

// select links
$row_link = DBOld::row('* FROM links WHERE user_id = ?', [$id]);

$hasItems = DBOld::exists('items WHERE user_id = ? AND status > 0', [$id]);
$productCount = DBOld::count(Product::TABLE . ' WHERE ' . Product::SELLER_INDEX . ' = ? AND status > 0', [$id]);
$likeN = DBOld::count('likes WHERE design_id IN (SELECT id FROM designs WHERE user_id = ?)', [$id]);
$follN = DBOld::count('followers WHERE follower_id = ?', [$id]);

if ($vip) $collN = DBOld::count('collections WHERE user_id = ?', [$id]);
else $collN = DBOld::count('collections WHERE user_id = ? AND ID IN (SELECT collection FROM designs)', [$id]);

// add folder
if (isset($_POST['add_folder'])) {
	$name = trim($_POST['name']);

	if (!empty($name)) {

		$collID = DBOld::add('collections (user_id, name) VALUES (?, ?)', [$id, $name]);

		redirect_old('/artist/collection?id=' . $collID);
	} else redirect_old();
}

$shop = new Shop();

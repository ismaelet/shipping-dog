<?php

use Ecommerce\Product;
use Ecommerce\Seller;
use Ecommerce\Shop;

// get variabiles
$id = isset($_GET['id']) ? (int) $_GET['id'] : false;

// redirect to user's shop if no ID is specified
if (!$id && is_seller()) redirect_old('?id=' . auth()->id());

// redirect if no shop was found
if (!DBOld::exists('stores WHERE userID = ?', [$id])) redirect_old('/shop');

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
$row_link = DBOld::row('* FROM links WHERE userID = ?', [$id]);

$hasItems = DBOld::exists('items WHERE userID = ? AND status > 0', [$id]);
$productCount = DBOld::count(Product::TABLE . ' WHERE ' . Product::SELLER_INDEX . ' = ? AND status > 0', [$id]);
$likeN = DBOld::count('likes WHERE desID IN (SELECT ID FROM designs WHERE userID = ?)', [$id]);
$follN = DBOld::count('followers WHERE followID = ?', [$id]);

if ($vip) $collN = DBOld::count('collections WHERE userID = ?', [$id]);
else $collN = DBOld::count('collections WHERE userID = ? AND ID IN (SELECT collection FROM designs)', [$id]);

// add folder
if (isset($_POST['add_folder'])) {
	$name = trim($_POST['name']);

	if (!empty($name)) {

		$collID = DBOld::add('collections (userID, name) VALUES (?, ?)', [$id, $name]);

		redirect_old('/artist/collection?id=' . $collID);
	} else redirect_old();
}

$shop = new Shop();

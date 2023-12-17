<?php

use Ecommerce\Product;
use Ecommerce\Seller;
use Ecommerce\Shop;

// get variabiles
$id = isset($_GET['id']) ? (int) $_GET['id'] : false;

// redirect to user's shop if no ID is specified
if (!$id && $user->is_seller()) redirect('?id=' . $user->id);

// redirect if no shop was found
if (!DB::exists('stores WHERE userID = ?', [$id])) redirect('/shop');

// setup shop
$seller = new Seller($id, ['name', 'status', 'followed', 'theme']);

if ($seller->hasCover) View::$thumbnail = $seller->cover[0];
if ($seller->description) View::$description = $seller->description;

// setup page
View::$title = $seller->name;

// special status
$owner = userId() == $id ? true : false;
$vip = $owner || admin() ? true : false;
$edit = isset($_GET['edit']) && $vip ? true : false;

// check if shop hasn't been approved yet
$public = $seller->status;
if (!$public && !$vip) redirect('/shop');

// get products data
$desMax = 10;
$collBlock = 10;
$shop_size = $id == 187 ? 10 : 20;

// select links
$row_link = DB::row('* FROM links WHERE userID = ?', [$id]);

$hasItems = DB::exists('items WHERE userID = ? AND status > 0', [$id]);
$productCount = DB::count(Product::TABLE . ' WHERE ' . Product::SELLER_INDEX . ' = ? AND status > 0', [$id]);
$likeN = DB::count('likes WHERE desID IN (SELECT ID FROM designs WHERE userID = ?)', [$id]);
$follN = DB::count('followers WHERE followID = ?', [$id]);

if ($vip) $collN = DB::count('collections WHERE userID = ?', [$id]);
else $collN = DB::count('collections WHERE userID = ? AND ID IN (SELECT collection FROM designs)', [$id]);

// add folder
if (isset($_POST['add_folder'])) {
	$name = trim($_POST['name']);

	if (!empty($name)) {

		$collID = DB::add('collections (userID, name) VALUES (?, ?)', [$id, $name]);

		redirect('/artist/collection?id=' . $collID);
	} else redirect();
}

$shop = new Shop();

<?php

use App\Models\Ecommerce\Seller;
use Legacy\Classes\Ecommerce\Product as LegacyProduct;
use Legacy\Classes\Ecommerce\Seller as LegacySeller;
use Legacy\Classes\Ecommerce\Shop;

// get variabiles
$id = request()->seller ?: request()->id;
$seller = Seller::find($id);
$legacySeller = new LegacySeller($id ?: 0);
$sellerUserId = $seller->user_id;

// redirect to user's shop if no ID is specified
if (!$seller) {
	redirect_old('?id=' . auth()->id());
}

if ($seller->hasCover) \Legacy\System\Classes\LegacyView::$thumbnail = $seller->cover[0];
if ($seller->description) \Legacy\System\Classes\LegacyView::$description = $seller->description;

// setup page
\Legacy\System\Classes\LegacyView::$title = $seller->name;

// special status
$owner = auth()->id() == $sellerUserId ? true : false;
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
$row_link = \Legacy\System\Classes\LegacyDB::row('* FROM links WHERE user_id = ?', [$sellerUserId]);

$hasItems = \Legacy\System\Classes\LegacyDB::exists('products WHERE user_id = ? AND status > 0', [$sellerUserId]);
$productCount = \Legacy\System\Classes\LegacyDB::count(LegacyProduct::TABLE . ' WHERE user_id = ? AND status > 0', [$sellerUserId]);
$likeN = \Legacy\System\Classes\LegacyDB::count('likes WHERE design_id IN (SELECT id FROM designs WHERE user_id = ?)', [$sellerUserId]);
$follN = \Legacy\System\Classes\LegacyDB::count('followers WHERE follower_id = ?', [$sellerUserId]);

if ($vip) $collN = \Legacy\System\Classes\LegacyDB::count('collections WHERE user_id = ?', [$sellerUserId]);
else $collN = \Legacy\System\Classes\LegacyDB::count('collections WHERE user_id = ? AND ID IN (SELECT collection FROM designs)', [$sellerUserId]);

// add folder
if (isset($_POST['add_folder'])) {
	$name = trim($_POST['name']);

	if (!empty($name)) {

		$collID = \Legacy\System\Classes\LegacyDB::add('collections (user_id, name) VALUES (?, ?)', [$sellerUserId, $name]);

		redirect_old('/artist/collection?id=' . $collID);
	} else redirect_old();
}

$shop = new Shop();

\Legacy\System\Classes\LegacyView::setTitle($seller->name);

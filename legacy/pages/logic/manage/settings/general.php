<?php

use Legacy\Classes\Ecommerce\Seller;

$saved = $_GET['saved'] ?? false;
$seller = new Seller(auth()->id());

// get socials data
$row_link = \Legacy\System\Classes\LegacyDB::row('* FROM links WHERE user_id = ?', [auth()->id()]);

if (!$row_link) \Legacy\System\Classes\LegacyDB::add('links (user_id) VALUES (?)', [auth()->id()]);

// edit shop data
if (isset($_POST['edit'])) {
	$name = trim($_POST['name']);
	$desc = trim($_POST['desc']);
	echo $sort = $_POST['sort'];

	if (empty($name)) $name = $seller->name;

	\Legacy\System\Classes\LegacyDB::update(Seller::TABLE . ' SET name = ?, description = ?, sort = ? WHERE ' . Seller::INDEX . ' = ?', [$name, $desc, $sort, auth()->id()]);

	redirect_old();
}

// edit profile icon
if (isset($_POST['edit-icon'])) {
	$fileData = [
		'url' => public_server_path('/uploads/users/icons/' . auth()->id()),
		'size' => [200, 200],
		'mode' => 'crop',
	];

	upload_img($fileData);

	redirect_old();
}

// edit cover
if (isset($_POST['edit-cover'])) {
	// upload HD
	$fileData = [
		'url' => public_server_path('/uploads/shops/covers/' . auth()->id()),
		'size' => 1920,
		'mode' => 'width',
	];

	upload_img($fileData);

	// upload LD
	$fileData = [
		'url' => public_server_path('/uploads/shops/covers/' . auth()->id() . '_m'),
		'size' => 400,
		'mode' => 'width',
		'quality' => 80
	];

	upload_img($fileData);

	redirect_old();
}

// edit socials
if (isset($_POST['edit-socials'])) {

	$site = trim(strtolower($_POST['site']));

	$fb = trim($_POST['fb']);
	if ($pos = strpos($fb, 'facebook.com/')) $fb = substr($fb, $pos + 13);
	$fb = strtok($fb, '/');
	$fb = str_replace('@', '', $fb);
	$fb = str_replace(' ', '', $fb);

	$inst = trim($_POST['inst']);
	if ($pos = strpos($inst, 'instagram.com/')) $inst = substr($inst, $pos + 14);
	$inst = strtok($inst, '/');
	$inst = str_replace('@', '', $inst);

	$twit = trim($_POST['twit']);
	if ($pos = strpos($twit, 'twitter.com/')) $twit = substr($twit, $pos + 12);
	$twit = strtok($twit, '/');
	$twit = str_replace('@', '', $twit);

	$yt = trim($_POST['yt']);
	if ($pos = strpos($yt, 'youtube.com/')) $yt = substr($yt, $pos + 12);
	$yt = strtok($yt, '/');

	if (!\Legacy\System\Classes\LegacyDB::exists('links WHERE user_id = ?', [auth()->id()])) \Legacy\System\Classes\LegacyDB::add('links (user_id) VALUE (?)', [auth()->id()]);

	\Legacy\System\Classes\LegacyDB::update('links SET site=?, facebook=?, instagram=?, twitter=?, youtube=? WHERE user_id = ?', [$site, $fb, $inst, $twit, $yt, auth()->id()]);

	redirect_old();
}

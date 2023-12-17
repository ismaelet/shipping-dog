<?php

use Ecommerce\Seller;

$saved = $_GET['saved'] ?? false;
$seller = new Seller(userId());

// get socials data
$row_link = DB::row('* FROM links WHERE userID=?', [userId()]);

if (!$row_link) DB::add('links (userID) VALUES (?)', [userId()]);

// edit shop data
if (isset($_POST['edit'])) {
	$name = trim($_POST['name']);
	$desc = trim($_POST['desc']);
	echo $sort = $_POST['sort'];

	if (empty($name)) $name = $seller->name;

	DB::update(Seller::TABLE . ' SET name = ?, description = ?, sort = ? WHERE ' . Seller::INDEX . ' = ?', [$name, $desc, $sort, userId()]);

	redirect();
}

// edit profile icon
if (isset($_POST['edit-icon'])) {
	$fileData = [
		'url' => public_server_path('/uploads/img_profile/' . userId()),
		'size' => [200, 200],
		'mode' => 'crop',
	];

	upload_img($fileData);

	redirect();
}

// edit cover
if (isset($_POST['edit-cover'])) {
	// upload HD
	$fileData = [
		'url' => public_server_path('/uploads/img_shop/' . userId()),
		'size' => 1920,
		'mode' => 'width',
	];

	upload_img($fileData);

	// upload LD
	$fileData = [
		'url' => public_server_path('/uploads/img_shop/' . userId() . '_m'),
		'size' => 400,
		'mode' => 'width',
		'quality' => 80
	];

	upload_img($fileData);

	redirect();
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

	if (!DB::exists('links WHERE userID=?', [userId()])) DB::add('links (userID) VALUE (?)', [userId()]);

	DB::update('links SET site=?, facebook=?, instagram=?, twitter=?, youtube=? WHERE userID=?', [$site, $fb, $inst, $twit, $yt, userId()]);

	redirect('&saved=socials#socials');
}

<?php

//delete the entry
if (isset($_POST['delete'])) {

	DBOld::delete('errors WHERE details=?', [$_POST['details']]);
}

//show the url
if (isset($_POST['view'])) {

	$url = empty($_POST['source']) ? $_POST['details'] : $_POST['source'];

	redirect_old('https://shippingdog.com' . $_POST['source']);
}

//add entry to ignore list
if (isset($_POST['ignore'])) {

	$id = $_POST['ID'];

	$url = DBOld::find('details FROM errors WHERE ID = ?', [$id]);

	if (!DBOld::exists('errors_whitelist WHERE url=?', [$url])) DBOld::add('errors_whitelist (url) VALUES (?)', [$url]);

	DBOld::delete('errors WHERE details=?', [$url]);

	redirect_old();
}

//select table
$tbl = 'errors';

//handle search
$query['filter'] = "name LIKE CONCAT('%',?,'%') OR category LIKE CONCAT('%',?,'%')";

//select fields
$inputs = [
	[
		'type' => 'hidden',
		'field' => 'ID'
	],
	[
		'type' => 'hidden',
		'field' => 'source'
	],
	[
		'type' => 'hidden',
		'field' => 'details'
	],
	[
		'field' => 'details',
		'title' => 'URL',
		'size' => 'xl',
	],
	[
		'field' => 'source',
		'title' => 'Origine',
		'size' => 'xl',
	],
	[
		'field' => 'time',
		'title' => 'Data',
		'size' => 'l',
	],
	[
		'type' => 'btn'
	],
];

$buttons = [
	[
		'type' => 'submit',
		'name' => 'view',
		'title' => 'Visualizza',
		'color' => 'gre',
	],
	[
		'type' => 'submit',
		'name' => 'ignore',
		'title' => 'Ignora',
		'color' => 'blu',
	],
	[
		'type' => 'submit',
		'title' => 'Elimina',
		'name' => 'delete',
		'color' => 'red',
		'conf' => "Sei sicuro di voler eliminare l\'elemento?",
	],
];

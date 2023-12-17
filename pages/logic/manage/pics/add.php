<?php

// setup page
global $user, $error;

$error = false;

// setup form
$search = [
	'tbl' => 'designs',
	'name-id' => 'ID',
	'name-show' => 'name',
	'filter' => 'userID:' . $user->id,
];

$inputs = [
	[
		['type' => 'lbl', 'text' => 'Nome e cognome (opzionali)'],
		[
			'name' => 'fname',
			'text' => 'Nome',
			'size' => 2,
		],
		[
			'name' => 'lname',
			'text' => 'Cognome',
			'size' => 2,
		],
	],
	[
		['type' => 'lbl', 'text' => 'Design'],
		[
			'type' => 'search',
			'name' => 'desID',
			'search' => $search,
			'text' => 'Design',
		],
		[
			'type' => 'error',
			'error' => 'des_name',
			'text' => 'Design non riconosciuto',
		],
	],
	[
		['type' => 'lbl', 'text' => 'Tipo di capo'],
		[
			'type' => 'select',
			'name' => 'type',
			'text' => 'Tipo di capo',
			'query' => 'tag, IF (gender=2, CONCAT(name_it," donna"), name_it) as name_full FROM items_types WHERE hidden=0 ORDER BY ID ASC',
			'field-name' => 'tag',
			'field-show' => 'name_full',
		],
	],
	[
		['type' => 'lbl', 'text' => 'Foto (JPG o PNG):'],
		[
			'type' => 'file',
			'name' => 'file',
			'required' => true,
		],
	],
	[
		[
			'type' => 'cbx',
			'text' => 'Dichiaro che il cliente mi ha confermato esplicitamente che posso caricare la sua foto su questo sito.',
			'required' => true,
		]
	],
];

// submit
if (isset($_POST['submit'])) {
	// get inputs data
	$fname = ucfirst(trim($_POST['fname']));
	$lname = ucfirst(trim($_POST['lname']));
	$desID = $_POST['desID'];
	$type = $_POST['type'];

	if (DB::exists('designs WHERE ID=? AND userID=?', [$desID, $user->id])) {

		$imgID = DB::add('home_pics (fname, lname, desID, type) VALUES (?, ?, ?, ?)', [$fname, $lname, $desID, $type]);

		$file = '/uploads/img_fans/' . $imgID . '.jpg';

		upload_img_OLD($file, 'img', '600', 'max');

		redirect('/manage/pics/list');
	} else
		$error = 'des_name';
}

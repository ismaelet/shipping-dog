<?php

// setup page
global $user, $error;

$error = false;

// setup form
$search = [
	'tbl' => 'designs',
	'name-id' => 'id',
	'name-show' => 'name',
	'filter' => 'user_id:' . auth()->id(),
];

$inputs = [
	[
		['type' => 'lbl', 'text' => 'Nome e cognome (opzionali)'],
		[
			'name' => 'first_name',
			'text' => 'Nome',
			'size' => 2,
		],
		[
			'name' => 'last_name',
			'text' => 'Cognome',
			'size' => 2,
		],
	],
	[
		['type' => 'lbl', 'text' => 'Design'],
		[
			'type' => 'search',
			'name' => 'design_id',
			'search' => 'designs',
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
			'query' => 'tag, IF (gender=2, CONCAT(name," donna"), name) as name_full FROM items_types WHERE hidden = 0 ORDER BY id ASC',
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
	$first_name = ucfirst(trim($_POST['first_name']));
	$last_name = ucfirst(trim($_POST['last_name']));
	$design_id = $_POST['design_id'];
	$type = $_POST['type'];

	if (\Legacy\System\Classes\LegacyDB::exists('designs WHERE id = ? AND user_id = ?', [$design_id, auth()->id()])) {

		$imgID = \Legacy\System\Classes\LegacyDB::add('home_pics (first_name, last_name, design_id, type) VALUES (?, ?, ?, ?)', [$first_name, $last_name, $design_id, $type]);

		$file = '/uploads/users/testimonials/' . $imgID . '.jpg';

		upload_img_OLD($file, 'img', '600', 'max');

		redirect_old('/manage/pics/list');
	} else
		$error = 'des_name';
}

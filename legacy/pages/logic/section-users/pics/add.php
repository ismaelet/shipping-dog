<?php

/*
* Page configuration
*/
// $pageConfig['confirm'] = $pageConfig['confirm'] ?? null;

/*
* Query configuration
*/
$id = $_GET['id'] ?? null;
$queryConfig['table'] = 'home_pics';
$queryConfig['index-field'] = 'id';
$queryConfig['title-field'] = 'first_name';

$sel = [
	'endpoint' => 'designs',
	'value-field' => 'id',
	'show-field' => 'fullName',
	'filter' => 'id,name',
];

$inputs = [
	[
		'name' => 'first_name',
		'text' => 'Nome e cognome',
		'text' => 'Nome',
		'size' => 'shr',
	],
	[
		'name' => 'last_name',
		'text' => 'Cognome',
		'size' => 'shr',
	],
	[
		'type' => 'select',
		'name' => 'type',
		'text' => 'Tipo',
		'query' => 'tag, IF (gender=2, CONCAT(name," donna"), name) AS name FROM items_types ORDER BY id ASC',
		'field-name' => 'tag',
		'field-show' => 'name',
	],
	[
		'type' => 'search',
		'text' => 'Design',
		'name' => 'design_id',
		'value' => $id ? \Legacy\System\Classes\LegacyDB::find('design_id FROM home_pics WHERE id = ?', [$id]) : null,
		'value-show' => $id ? \Legacy\System\Classes\LegacyDB::find('name FROM designs WHERE id IN (SELECT design_id FROM home_pics WHERE id = ?)', [$id]) : null,
		'search' => 'designs',
		'required' => true,
	],
	[
		'type' => 'file',
		'name' => 'file',
		'text' => 'Foto',
		'required' => $id ? false : true,
	]
];

//add entry
function _expand_edit($tbl, $inputs, $index, $id) {
	form_db_update($tbl, $inputs, $index, $id);

	_upload_file($id);
}

function _expand_add($tbl, $inputs) {
	$id = form_db_add($tbl, $inputs);

	_upload_file($id);
}

function _upload_file($id) {
	if ($_FILES['file']['name']) {
		$file_data = [
			'url' => public_server_path('/uploads/users/testimonials/' . $id . '.jpg'),
			'size' => 600,
			'type' => 'jpg',
			'mode' => 'height',
			'quality' => 80
		];

		upload_img($file_data);
	}
}

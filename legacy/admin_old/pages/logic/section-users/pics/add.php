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
$queryConfig['index-field'] = 'ID';
$queryConfig['title-field'] = 'fname';

$sel = [
	'endpoint' => 'designs',
	'value-field' => 'id',
	'show-field' => 'fullName',
	'filter' => 'id,name',
];

$inputs = [
	[
		'name' => 'fname',
		'text' => 'Nome e cognome',
		'text' => 'Nome',
		'size' => 'shr',
	],
	[
		'name' => 'lname',
		'text' => 'Cognome',
		'size' => 'shr',
	],
	[
		'type' => 'select',
		'name' => 'type',
		'text' => 'Tipo',
		'query' => 'tag, IF (gender=2, CONCAT(name_it," donna"), name_it) AS name FROM items_types ORDER BY ID ASC',
		'field-name' => 'tag',
		'field-show' => 'name',
	],
	[
		'type' => 'search',
		'text' => 'Design',
		'name' => 'desID',
		'value' => $id ? DBOld::find('desID FROM home_pics WHERE ID = ?', [$id]) : null,
		'value-show' => $id ? DBOld::find('name FROM designs WHERE ID IN (SELECT desID FROM home_pics WHERE ID = ?)', [$id]) : null,
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

<?php

/*
* Page configuration
*/
// $pageConfig['add'] = false;
$pageConfig['row-action'] = 'add?id={{id}}';

/*
* Query configuration
*/
$queryConfig['table'] = 'home_pics';
$queryConfig['index'] = 'id';
$queryConfig['where-search'] = "first_name LIKE CONCAT('%',?,'%') OR last_name LIKE CONCAT('%',?,'%')";

//select fields
$fields = [
	['title' => 'Nome', 'name' => 'first_name', 'size' => 1],
	['title' => 'Cognome', 'name' => 'last_name', 'size' => 1],
	['title' => 'Design', 'name' => 'design_id', 'type' => 'find', 'find' => 'name FROM designs WHERE id = ?',],
	['title' => 'Tipo', 'name' => 'type', 'type' => 'find', 'find' => 'name FROM product_types WHERE tag = ?',],
	['title' => 'Data', 'name' => 'time',],

	['type' => 'hidden', 'name' => 'id'],

	['type' => 'btn'],
];

//buttons
$buttons = [
	['type' => 'edit'],
	['type' => 'delete'],
];

if (isset($_POST['delete'])) {

	$id = $_POST['id'];

	\Legacy\System\Classes\LegacyDB::delete('home_pics WHERE id = ?', [$id]);

	if (file_exists($file = PUBLIC_SERVER_ROOT . '/uploads/users/testimonials/' . $id . '.jpg')) unlink($file);
}

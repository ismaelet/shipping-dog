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
$queryConfig['index'] = 'ID';
$queryConfig['where-search'] = "fname LIKE CONCAT('%',?,'%') OR lname LIKE CONCAT('%',?,'%')";

//select fields
$fields = [
	['title' => 'Nome', 'name' => 'fname', 'size' => 1],
	['title' => 'Cognome', 'name' => 'lname', 'size' => 1],
	['title' => 'Design', 'name' => 'desID', 'type' => 'find', 'find' => 'name FROM designs WHERE ID=?',],
	['title' => 'Tipo', 'name' => 'type', 'type' => 'find', 'find' => 'name_it FROM items_types WHERE tag=?',],
	['title' => 'Data', 'name' => 'time',],

	['type' => 'hidden', 'name' => 'ID'],

	['type' => 'btn'],
];

//buttons
$buttons = [
	['type' => 'edit'],
	['type' => 'delete'],
];

if (isset($_POST['delete'])) {

	$id = $_POST['ID'];

	DB::delete('home_pics WHERE ID=?', [$id]);

	if (file_exists($file = PUBLIC_SERVER_ROOT . '/uploads/img_fans/' . $id . '.jpg')) unlink($file);
}

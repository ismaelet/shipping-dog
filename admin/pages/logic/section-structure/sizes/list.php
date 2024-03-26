<?php

/*
* Page configuration
*/
$pageConfig['row-action'] = 'add?id={{id}}';

/*
* Query configuration
*/
$queryConfig['table'] = 'items_sizes';
$queryConfig['index'] = 'ID';
$query['where-search'] = "
	ID = ? OR
	tag LIKE CONCAT('%',?,'%') OR
	name LIKE CONCAT('%',?,'%')
";

/*
* Fields
*/
$fields = [
	['title' => 'ID', 'name' => 'ID'],
	['title' => 'Nome', 'name' => 'name', 'size' => 1],
	['title' => 'Sovrapprezzo', 'name' => 'price', 'size' => 1, 'parse' => 'price'],

	['type' => 'btn'],
];

$buttons = [
	['title' => 'Modifica', 'url' => 'add?id={{ID}}'],
];

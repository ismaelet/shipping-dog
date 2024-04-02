<?php

/*
* Page configuration
*/
$pageConfig['row-action'] = 'add?id={{id}}';

/*
* Query configuration
*/
$queryConfig['index'] = 'id';
$queryConfig['table'] = 'variables';
$queryConfig['order-index'] = 'name';
$queryConfig['order-direction'] = 'ASC';
$queryConfig['where-search'] = "name LIKE CONCAT('%',?,'%')";

/*
* List fields
*/
$fields = [
	['title' => 'Nome', 'name' => 'name',],
	['title' => 'Valore', 'name' => 'value', 'size' => 1],

	['type' => 'btn'],
];

$buttons = [
	['type' => 'edit'],
	['type' => 'delete'],
];

<?php

// select table
$tbl = 'sales';

// handle search
$query['filter'] = "desID IN (SELECT ID FROM designs WHERE name LIKE CONCAT('%',?,'%')) OR storID IN (SELECT userID FROM stores WHERE name LIKE CONCAT('%',?,'%'))";

// select fields
$inputs = [
	['type' => 'hidden', 'field' => 'ID'],

	['title' => 'ID', 'field' => 'ID', 'size' => 's'],
	['title' => 'Design', 'field' => 'desID', 'find' => 'name FROM designs WHERE ID=?', 'size' => 'm'],
	['title' => 'Negozio', 'field' => 'storID', 'find' => 'name FROM stores WHERE userID=?', 'size' => 'm'],
	['title' => 'Tipo', 'field' => 'typeID', 'find' => 'name_it FROM items_types WHERE ID=?', 'size' => 'm'],
	['title' => 'Colore', 'field' => 'colorID', 'find' => 'name_it FROM items_colors WHERE ID=?', 'size' => 'm'],
	['title' => 'Codice', 'field' => 'code', 'size' => 'm'],
	['title' => 'Qt.', 'field' => 'amount', 'size' => 's'],
	['title' => 'Guadagno', 'field' => 'gain', 'show' => 'num2', 'size' => 's'],
	['title' => 'Data', 'field' => 'time', 'size' => 'm'],

	['type' => 'btn'],
];

$buttons = [
	['arch' => 'delete'],
];

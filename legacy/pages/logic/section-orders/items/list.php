<?php

//setup page
$id = $_GET['id'] ?? false;

if (!DBOld::exists('carts WHERE status>0 AND ID = ?', $id)) redirect_old('/orders/list');

$index = 'ID';
$tbl = 'orders';
$query['search-bar'] = false;
$query['addID'] = $id;

$this->title('Articoli dell\'ordine ' . $id);

//handle search
$query['where'] = 'cartID=' . $id;

//select fields
$fields = [
	['title' => 'Design', 'name' => 'itemID', 'type' => 'find', 'find' => 'name FROM designs WHERE ID IN (SELECT desID FROM items WHERE ID = ?)', 'size' => 'l'],
	['title' => 'Tipo', 'name' => 'typeID', 'type' => 'find', 'find' => 'name FROM items_types WHERE ID = ?', 'size' => 'm'],
	['title' => 'Modello', 'name' => 'gendID', 'type' => 'find', 'find' => 'name_it FROM items_genders WHERE ID = ?', 'size' => 'm'],
	['title' => 'Colore', 'name' => 'colorID', 'type' => 'find', 'find' => 'name_it FROM items_colors WHERE ID = ?', 'size' => 'm'],
	['title' => 'Taglia', 'name' => 'sizeID', 'type' => 'find', 'find' => 'name FROM items_sizes WHERE ID = ?', 'size' => 'm'],

	['type' => 'btn'],
];

$buttons = [
	['type' => 'edit'],
	['type' => 'delete'],
];

<?php

//setup page
$id = $_GET['id'] ?? false;

if (!\Legacy\System\Classes\LegacyDB::exists('carts WHERE status>0 AND ID = ?', $id)) redirect_old('/orders/list');

$index = 'id';
$tbl = 'orders';
$query['search-bar'] = false;
$query['addID'] = $id;

$this->title('Articoli dell\'ordine ' . $id);

//handle search
$query['where'] = 'cart_id=' . $id;

//select fields
$fields = [
	['title' => 'Design', 'name' => 'product_id', 'type' => 'find', 'find' => 'name FROM designs WHERE id IN (SELECT design_id FROM items WHERE id = ?)', 'size' => 'l'],
	['title' => 'Tipo', 'name' => 'type_id', 'type' => 'find', 'find' => 'name FROM items_types WHERE id = ?', 'size' => 'm'],
	['title' => 'Modello', 'name' => 'gender_id', 'type' => 'find', 'find' => 'name FROM items_genders WHERE id = ?', 'size' => 'm'],
	['title' => 'Colore', 'name' => 'color_id', 'type' => 'find', 'find' => 'name FROM items_colors WHERE id = ?', 'size' => 'm'],
	['title' => 'Taglia', 'name' => 'size_id', 'type' => 'find', 'find' => 'name FROM items_sizes WHERE id = ?', 'size' => 'm'],

	['type' => 'btn'],
];

$buttons = [
	['type' => 'edit'],
	['type' => 'delete'],
];

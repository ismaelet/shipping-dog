<?php

/*
* Query config
*/
$queryConfig['table'] = 'inventory';
$queryConfig['index'] = 'id';
$queryConfig['select'] = ['inventory.id', 'inventory.time', 'inventory.design_id', 'designs.name', 'product_types.name as typeName', 'items_colors.name as colorName', 'items_sizes.name as sizeName', 'product_positions.name as positionName', 'extras.name as logoName', '(design_id IN (SELECT id FROM designs WHERE status = 1) AND designs.user_id IN (SELECT user_id FROM stores)) as inInventory'];
$queryConfig['from'] = '
	inventory
		INNER JOIN designs
			ON inventory.design_id = designs.id
		LEFT JOIN product_types
			ON inventory.type_id = product_types.id
		LEFT JOIN items_colors
			ON inventory.color_id = items_colors.id
		LEFT JOIN items_sizes
			ON inventory.size_id = items_sizes.id
		LEFT JOIN product_positions
			ON inventory.position_id = product_positions.id
		LEFT JOIN extras
			ON inventory.logo_id = extras.id
';
$queryConfig['where-search'] = "
	designs.name LIKE CONCAT('%',?,'%') OR
	product_types.name LIKE CONCAT('%',?,'%') OR
	items_colors.name LIKE CONCAT('%',?,'%') OR
	items_sizes.name LIKE CONCAT('%',?,'%') OR
	product_positions.name LIKE CONCAT('%',?,'%') OR
	extras.name LIKE CONCAT('%',?,'%')
";

// select fields
$fields = [
	['title' => 'id', 'name' => 'id',],
	['title' => 'Design', 'name' => 'name', 'size' => 2],
	['title' => 'Tipo', 'name' => 'typeName',],
	['title' => 'Colore', 'name' => 'colorName', 'size' => 1],
	['title' => 'Taglia', 'name' => 'sizeName', 'size' => 1],
	['title' => 'Posizione', 'name' => 'positionName', 'size' => 1],
	['title' => 'Logo', 'name' => 'logoName'],
	['title' => 'Acquistabile', 'name' => 'inInventory', 'type' => 'boolean-icon'],
	['title' => 'Data', 'name' => 'time', 'parse' => 'date-day'],

	['type' => 'btn'],
];

$buttons = [
	['type' => 'edit'],
	['type' => 'delete'],
];

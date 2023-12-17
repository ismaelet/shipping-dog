<?php

/*
* Query config
*/
$queryConfig['table'] = 'inventory';
$queryConfig['index'] = 'id';
$queryConfig['select'] = ['inventory.id', 'inventory.time', 'inventory.designId', 'designs.name', 'items_types.name as typeName', 'items_colors.name_it as colorName', 'items_sizes.name as sizeName', 'items_positions.name_it as positionName', 'extras.name as logoName', '(designId IN (SELECT ID FROM designs WHERE status = 1) AND designs.userID IN (SELECT userID FROM stores)) as inInventory'];
$queryConfig['from'] = '
	inventory
		INNER JOIN designs
			ON inventory.designId = designs.ID
		LEFT JOIN items_types
			ON inventory.typeId = items_types.ID
		LEFT JOIN items_colors
			ON inventory.colorId = items_colors.ID
		LEFT JOIN items_sizes
			ON inventory.sizeId = items_sizes.ID
		LEFT JOIN items_positions
			ON inventory.positionId = items_positions.ID
		LEFT JOIN extras
			ON inventory.logoId = extras.ID
';
$queryConfig['where-search'] = "
	designs.name LIKE CONCAT('%',?,'%') OR
	items_types.name LIKE CONCAT('%',?,'%') OR
	items_colors.name_it LIKE CONCAT('%',?,'%') OR
	items_sizes.name LIKE CONCAT('%',?,'%') OR
	items_positions.name_it LIKE CONCAT('%',?,'%') OR
	extras.name LIKE CONCAT('%',?,'%')
";

// select fields
$fields = [
	['title' => 'ID', 'name' => 'id',],
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

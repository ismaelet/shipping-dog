<?php

$id = $_GET['id'] ?? false;

/*
* Query config
*/
$queryConfig['table'] = 'inventory';
$queryConfig['from'] = 'inventory LEFT JOIN designs ON inventory.designId = designs.ID';
$queryConfig['index-field'] = 'inventory.id';
$queryConfig['title-field'] = 'name';

// form
$inputs = [
	'design' => [
		'type' => 'search',
		'search' => 'designs',
		'name' => 'designId',
		'text' => 'Design',
		'required' => true
	],
	[
		'type' => 'select',
		'name' => 'typeId',
		'text' => 'Tipo',
		'query' => '* FROM items_types ORDER BY ID ASC',
		'field-name' => 'ID',
		'field-show' => 'name',
	],
	[
		'type' => 'select',
		'name' => 'colorId',
		'text' => 'Colore',
		'query' => '* FROM items_colors ORDER BY ID ASC',
		'field-name' => 'ID',
		'field-show' => 'name_it',
		'empty' => true,
	],
	[
		'type' => 'select',
		'name' => 'sizeId',
		'text' => 'Taglia',
		'query' => '* FROM items_sizes ORDER BY ID ASC',
		'field-name' => 'ID',
		'field-show' => 'name',
		'empty' => true,
	],
	[
		'type' => 'select',
		'name' => 'positionId',
		'text' => 'Posizione',
		'query' => '* FROM items_positions ORDER BY ID ASC',
		'field-name' => 'ID',
		'field-show' => 'name_it',
	],
	[
		'type' => 'select',
		'name' => 'logoId',
		'text' => 'Logo',
		'query' => '* FROM extras WHERE ID>0 ORDER BY name ASC',
		'field-name' => 'ID',
		'field-show' => 'name',
		'empty' => true,
	],
];

if ($id) {
	$designId = DBOld::find('designId FROM inventory WHERE id = ?', $id);
	$designName = DBOld::find('name FROM designs WHERE ID = ?', $designId);

	$inputs['design']['value'] = $designId;
	$inputs['design']['value-show'] = $designName;
}

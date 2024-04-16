<?php

$id = $_GET['id'] ?? false;

/*
* Query config
*/
$queryConfig['table'] = 'inventory';
$queryConfig['from'] = 'inventory LEFT JOIN designs ON inventory.design_id = designs.id';
$queryConfig['index-field'] = 'inventory.id';
$queryConfig['title-field'] = 'name';

// form
$inputs = [
	'design' => [
		'type' => 'search',
		'search' => 'designs',
		'name' => 'design_id',
		'text' => 'Design',
		'required' => true
	],
	[
		'type' => 'select',
		'name' => 'type_id',
		'text' => 'Tipo',
		'query' => '* FROM items_types ORDER BY id ASC',
		'field-name' => 'id',
		'field-show' => 'name',
	],
	[
		'type' => 'select',
		'name' => 'colorId',
		'text' => 'Colore',
		'query' => '* FROM items_colors ORDER BY id ASC',
		'field-name' => 'id',
		'field-show' => 'name',
		'empty' => true,
	],
	[
		'type' => 'select',
		'name' => 'size_id',
		'text' => 'Taglia',
		'query' => '* FROM items_sizes ORDER BY id ASC',
		'field-name' => 'id',
		'field-show' => 'name',
		'empty' => true,
	],
	[
		'type' => 'select',
		'name' => 'position_id',
		'text' => 'Posizione',
		'query' => '* FROM items_positions ORDER BY id ASC',
		'field-name' => 'id',
		'field-show' => 'name',
	],
	[
		'type' => 'select',
		'name' => 'logo_id',
		'text' => 'Logo',
		'query' => '* FROM extras WHERE id>0 ORDER BY name ASC',
		'field-name' => 'id',
		'field-show' => 'name',
		'empty' => true,
	],
];

if ($id) {
	$design_id = DBOld::find('design_id FROM inventory WHERE id = ?', $id);
	$designName = DBOld::find('name FROM designs WHERE id = ?', $design_id);

	$inputs['design']['value'] = $design_id;
	$inputs['design']['value-show'] = $designName;
}

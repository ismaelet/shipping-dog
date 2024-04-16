<?php

// setup page
$free = true;

// inputs
$inputs = [
	[
		'type' => 'error',
		'error' => 'types-empty',
		'text' => 'Devi selezionare almeno un capo',
	],
	[
		'type' => 'done',
		'text' => 'Modifica apportata con successo',
	],
	[
		[
			'type' => 'lbl',
			'text' => 'Colore',
		],
		[
			'type' => 'select',
			'text' => 'Colore',
			'name' => 'color',
			'query' => "* FROM items_colors ORDER BY name ASC",
			'field-name' => 'id',
			'field-show' => 'name',
			'value' => $_POST['color'] ?? false,
		],
	]

];

// fetch types
$types = DBOld::select('* FROM items_types ORDER BY hidden ASC, category ASC, id ASC');

$i = 0;
while ($type = $types->fetch_assoc()) {

	$input = [];

	$input = [
		'type' => 'cbx',
		'name' => 'type[]',
		'value' => $type['id'],
		'text' => $type['name'] . ($type['gender'] == 2 ? ' Donna' : ''),
	];

	if (!$i) {

		$input['lbl'] = 'Capi';
		$i = 1;
	}

	$inputs[] = $input;
}

// add color
if (isset($_POST['add-color'])) {
	if (isset($_POST['type'])) {
		$color = new Color($_POST['color']);

		DBOld::update('items_colors SET hidden=0 WHERE tag = ?', [$color->slug]);

		foreach ($_POST['type'] as $type_id) {
			// update type colors
			DBOld::update("items_types SET colors=CONCAT(colors,',',?) WHERE id = ? AND NOT FIND_IN_SET(?, colors)", [$color->slug, $type_id, $color->slug]);

			// update items color
			DBOld::update("items SET colors=CONCAT(colors,',',?) WHERE type_id = ? AND NOT FIND_IN_SET(?, colors) AND design_id NOT IN (SELECT id FROM designs WHERE custom=1)", [$color->slug, $type_id, $color->slug]);

			// set as main color if there's no main color
			DBOld::update("items SET color_id = ? WHERE type_id = ? AND color_id=0", [$color->id, $type_id]);
		}

		$done = true;
	} else $error = 'types-empty';
}

// remove color
if (isset($_POST['remove-color'])) {
	if (isset($_POST['type'])) {
		$color = new Color($_POST['color']);

		foreach ($_POST['type'] as $type_id) {
			DBOld::update("items_types SET colors = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', colors, ','), '," . $color->slug . ",', ',')) WHERE id = ?", [$type_id]);
			DBOld::update("items SET colors = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', colors, ','), '," . $color->slug . ",', ',')) WHERE type_id = ? AND design_id NOT IN (SELECT id FROM designs WHERE custom=1)", [$type_id]);
		}

		$done = true;
	} else $error = 'types-empty';
}

// remove color
if (isset($_POST['remove-color-all'])) {
	$color = new Color($_POST['color']);

	DBOld::update('items_colors SET hidden=1 WHERE id = ?', [$color->id]);
	DBOld::update("items_types SET colors = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', colors, ','), '," . $color->slug . ",', ','))");
	DBOld::update("items SET colors = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', colors, ','), '," . $color->slug . ",', ',')) WHERE design_id NOT IN (SELECT id FROM designs WHERE custom=1)");

	$done = true;
}

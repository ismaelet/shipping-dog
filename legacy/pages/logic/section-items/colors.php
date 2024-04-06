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
			'query' => "* FROM items_colors ORDER BY name_it ASC",
			'field-name' => 'ID',
			'field-show' => 'name_it',
			'value' => $_POST['color'] ?? false,
		],
	]

];

// fetch types
$types = DBOld::select('* FROM items_types ORDER BY hidden ASC, category ASC, ID ASC');

$i = 0;
while ($type = $types->fetch_assoc()) {

	$input = [];

	$input = [
		'type' => 'cbx',
		'name' => 'type[]',
		'value' => $type['ID'],
		'text' => $type['name_it'] . ($type['gender'] == 2 ? ' Donna' : ''),
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

		foreach ($_POST['type'] as $typeId) {
			// update type colors
			DBOld::update("items_types SET colors=CONCAT(colors,',',?) WHERE ID = ? AND NOT FIND_IN_SET(?, colors)", [$color->slug, $typeId, $color->slug]);

			// update items color
			DBOld::update("items SET colors=CONCAT(colors,',',?) WHERE typeID = ? AND NOT FIND_IN_SET(?, colors) AND desID NOT IN (SELECT ID FROM designs WHERE custom=1)", [$color->slug, $typeId, $color->slug]);

			// set as main color if there's no main color
			DBOld::update("items SET colorID = ? WHERE typeID = ? AND colorID=0", [$color->id, $typeId]);
		}

		$done = true;
	} else $error = 'types-empty';
}

// remove color
if (isset($_POST['remove-color'])) {
	if (isset($_POST['type'])) {
		$color = new Color($_POST['color']);

		foreach ($_POST['type'] as $typeID) {
			DBOld::update("items_types SET colors = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', colors, ','), '," . $color->slug . ",', ',')) WHERE ID = ?", [$typeID]);
			DBOld::update("items SET colors = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', colors, ','), '," . $color->slug . ",', ',')) WHERE typeID = ? AND desID NOT IN (SELECT ID FROM designs WHERE custom=1)", [$typeID]);
		}

		$done = true;
	} else $error = 'types-empty';
}

// remove color
if (isset($_POST['remove-color-all'])) {
	$color = new Color($_POST['color']);

	DBOld::update('items_colors SET hidden=1 WHERE ID = ?', [$color->id]);
	DBOld::update("items_types SET colors = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', colors, ','), '," . $color->slug . ",', ','))");
	DBOld::update("items SET colors = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', colors, ','), '," . $color->slug . ",', ',')) WHERE desID NOT IN (SELECT ID FROM designs WHERE custom=1)");

	$done = true;
}

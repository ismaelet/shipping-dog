<?php

global $id;

//setup page
$typeName = \Legacy\System\Classes\LegacyDB::find('name FROM items_types WHERE id = ?', $id);

$tbl = 'items_types_measures';
$index = 'type_id';
$title = 'Modifica dimensioni di "' . $typeName . '"';

//form
$inputs = [];

$measures = \Legacy\System\Classes\LegacyDB::select('* FROM ' . $tbl . ' WHERE type_id=? AND size<>"mat"', $id);

while ($measure = $measures->fetch_assoc()) {

	$inputs[] = [
		['type' => 'lbl', 'text' => 'Taglia ' . strtoupper($measure['size'])],
		['name' => $measure['size'], 'text' => 'Dimensioni (separare con virgola)', 'value' => $measure['val']],
	];
}

function _expand_edit($tbl, $inputs, $index, $id) {

	$measures = \Legacy\System\Classes\LegacyDB::select('size FROM ' . $tbl . ' WHERE type_id=? AND size<>"mat"', $id);

	while ($measure = $measures->fetch_assoc()) {

		\Legacy\System\Classes\LegacyDB::update($tbl . ' SET val=? WHERE type_id=? AND size=?', [$_POST[$measure['size']], $id, $measure['size']]);
	}
}

<?php

global $id;

//setup page
$typeName = DBOld::find('name FROM items_types WHERE ID = ?', $id);

$tbl = 'items_types_measures';
$index = 'typeID';
$title = 'Modifica dimensioni di "' . $typeName . '"';

//form
$inputs = [];

$measures = DBOld::select('* FROM ' . $tbl . ' WHERE typeId=? AND size<>"mat"', $id);

while ($measure = $measures->fetch_assoc()) {

	$inputs[] = [
		['type' => 'lbl', 'text' => 'Taglia ' . strtoupper($measure['size'])],
		['name' => $measure['size'], 'text' => 'Dimensioni (separare con virgola)', 'value' => $measure['val']],
	];
}

function _expand_edit($tbl, $inputs, $index, $id) {

	$measures = DBOld::select('size FROM ' . $tbl . ' WHERE typeId=? AND size<>"mat"', $id);

	while ($measure = $measures->fetch_assoc()) {

		DBOld::update($tbl . ' SET val=? WHERE typeId=? AND size=?', [$_POST[$measure['size']], $id, $measure['size']]);
	}
}

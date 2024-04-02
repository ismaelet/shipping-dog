<?php

// select table
$tbl = 'payment_requests';
$index = 'date';
$add = false;

// handle search
$query['filter'] = "name LIKE CONCAT('%',?,'%') OR userID = ? OR userID IN (SELECT ID FROM users WHERE username LIKE CONCAT('%',?,'%') OR firstname LIKE CONCAT('%',?,'%') OR lastname LIKE CONCAT('%',?,'%'))";

// select fields
$fields = [
	['title' => 'ID', 'name' => 'shopId', 'size' => 1],
	['title' => 'Nome', 'name' => 'name', 'size' => 3],
	['title' => 'Data di nascita', 'name' => 'birthdate', 'size' => 2],
	['title' => 'Luogo di nascita', 'name' => 'birthplace', 'size' => 3],
	['title' => 'Dati anagrafici', 'name' => 'birthdata', 'size' => 4],
	['title' => 'Indirizzo', 'name' => 'address', 'size' => 4],
	['title' => 'Data', 'name' => 'date', 'size' => 2],

	['type' => 'hidden', 'name' => 'userID'],

	['type' => 'btn'],
];

function _expand_results(&$entry, $field, $key, $row) {
	if ($field['name'] == 'name') {
		$entry['value'] = $row['firstname'] . ' ' . $row['lastname'];
	} else if ($field['name'] == 'birthplace') {
		$entry['value'] = $row['birthplace'] . ' (' . strtoupper($row['birthprovince']) . ')';
	} else if ($field['name'] == 'birthdata') {
		$entry['value'] = strtoupper($row['cf']) . '<br>' . strtoupper($row['nationalId']);
	} else if ($field['name'] == 'address') {
		$entry['value'] = $row['address'] . '<br>' . $row['zip'] . ' ' . $row['city'] . ' (' . strtoupper($row['province']) . ')';
	}
}

$buttons = [
	//['title' => 'Negozio', 'url' => sysDB('pd').'/artist', 'url_index_field' => 'userID', 'url_index' => 'id'],
	['arch' => 'delete'],
];

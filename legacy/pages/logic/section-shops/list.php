<?php

$pageConfig['add'] = false;

$queryConfig['table'] = 'stores';
$queryConfig['index'] = 'user_id';
$queryConfig['where-search'] = "name LIKE CONCAT('%',?,'%') OR user_id = ? OR user_id IN (SELECT id FROM users WHERE username LIKE CONCAT('%',?,'%') OR first_name LIKE CONCAT('%',?,'%') OR last_name LIKE CONCAT('%',?,'%'))";

// select fields
$fields = [
	['title' => 'id', 'name' => 'user_id',],
	['title' => 'Nome', 'name' => 'name', 'size' => 3],
	['title' => 'Descrizione', 'name' => 'description', 'size' => 4],
	['title' => 'Pubblicato', 'name' => 'status', 'type' => 'boolean-icon'],
	['title' => 'Vendite totali', 'name' => 'sales', 'parse' => 'int'],
	['title' => 'Vendite recenti', 'name' => 'recent_sales', 'parse' => 'int'],
	['title' => 'Creato', 'name' => 'time'],

	['type' => 'btn'],
];

$buttons = [
	['title' => 'Attiva', 'name' => 'activate', 'color' => 'green'],
	['title' => 'Disattiva', 'name' => 'deactivate', 'color' => 'purple'],
	['title' => 'Mostra', 'url' => '/artist?id={{user_id}}'],
	['type' => 'delete'],
];

function _expand_buttons(&$button, $buttonKey, $queryRow) {
	if ($queryRow['status'] && $button['title'] == 'Attiva') {
		$button = null;
	}

	if (!$queryRow['status'] && $button['title'] == 'Disattiva') {
		$button = null;
	}
}

/*
* Form
*/
if (isset($_POST['activate'])) {
	DBOld::update('stores SET status=1 WHERE user_id = ?', [$_POST['user_id']]);
}

if (isset($_POST['deactivate'])) {
	DBOld::update('stores SET status=0 WHERE user_id = ?', [$_POST['user_id']]);
}

if (isset($_POST['delete'])) {
	delete_shop($_POST['user_id']);
}

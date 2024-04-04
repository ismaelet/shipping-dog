<?php

$pageConfig['add'] = false;

$queryConfig['table'] = 'stores';
$queryConfig['index'] = 'userID';
$queryConfig['where-search'] = "name LIKE CONCAT('%',?,'%') OR userID = ? OR userID IN (SELECT ID FROM users WHERE username LIKE CONCAT('%',?,'%') OR firstname LIKE CONCAT('%',?,'%') OR lastname LIKE CONCAT('%',?,'%'))";

// select fields
$fields = [
	['title' => 'ID', 'name' => 'userID',],
	['title' => 'Nome', 'name' => 'name', 'size' => 3],
	['title' => 'Descrizione', 'name' => 'description', 'size' => 4],
	['title' => 'Pubblicato', 'name' => 'status', 'type' => 'boolean-icon'],
	['title' => 'Vendite totali', 'name' => 'sales', 'parse' => 'int'],
	['title' => 'Vendite recenti', 'name' => 'rsales', 'parse' => 'int'],
	['title' => 'Creato', 'name' => 'time'],

	['type' => 'btn'],
];

$buttons = [
	['title' => 'Attiva', 'name' => 'activate', 'color' => 'green'],
	['title' => 'Disattiva', 'name' => 'deactivate', 'color' => 'purple'],
	['title' => 'Mostra', 'url' => PUBLIC_CLIENT_ROOT . '/artist?id={{userID}}'],
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
	DB::update('stores SET status=1 WHERE userID = ?', [$_POST['userID']]);
}

if (isset($_POST['deactivate'])) {
	DB::update('stores SET status=0 WHERE userID = ?', [$_POST['userID']]);
}

if (isset($_POST['delete'])) {
	delete_shop($_POST['userID']);
}

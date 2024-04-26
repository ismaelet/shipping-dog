<?php

use App\Http\Controllers\Admin\FormController;

$id = $_GET['id'] ?? null;
$entry = ViewOld::get('entry') ?? null;
$queryConfig = ViewOld::get('queryConfig');
$pageConfig = ViewOld::get('pageConfig') ?? [];
$listFilters = $_GET['list-filters'] ?? null;
$error = ViewOld::get('error') ?? false;
$done = ViewOld::get('done') ?? false;
$inputs = ViewOld::get('inputs') ?? null;
$pageConfig['edit'] = (bool) $id;

/*
* Page configuration
*	? (string) title: the page title
*	? (bool) free: active if the pace has premade static content
*	? (bool) centered: if set the all the texts inside the form will have a centered alignement
*	? (bool) upload: true if the form uploads files
*	? (string) confirm: is set, the user will have to agree to the confirmation message to procede
*	? (string) return-page: the relative path of the page to go back after the form submit
*/
$pageConfig['title'] = $pageConfig['title'] ?? ViewOld::$title;
$pageConfig['free'] = $pageConfig['free'] ?? false;
$pageConfig['centered'] = $pageConfig['centered'] ?? false;
$pageConfig['upload'] = $pageConfig['upload'] ?? false;
$pageConfig['confirm'] = $pageConfig['confirm'] ?? null;
$pageConfig['return-page'] = $pageConfig['return-page'] ?? 'index';

/*
* Query configuration
*	(string) table: the table to interact with
*	(string) index-field: the name of the index field 
*	(string) title-field: the name of the field that will be used to generate the title of the page
*	? (array) select: the fields to be fetched
*	? (string) from: the table from which to fetch the data
*/
$queryConfig['select'] = isset($queryConfig['select']) ? implode(', ', $queryConfig['select']) : '*';
$queryConfig['from'] = $queryConfig['from'] ?? ($queryConfig['table'] ?? null);

// generate the return url
$pageConfig['return-url'] = $listFilters ? $pageConfig['return-page'] . '?' . urldecode($listFilters) : $pageConfig['return-page'];
$pageConfig['return-url'] .= $pageConfig['edit'] ? '#row' . $id : null;

// fetch entry data
if ($pageConfig['edit']) {
	$entry = $entry ?? DBOld::row($queryConfig['select'] . ' FROM ' . $queryConfig['from'] . ' WHERE ' . $queryConfig['index-field'] . '=?', [$id]);
	$pageConfig['title'] = ('Edit') . ': ' . $entry[$queryConfig['title-field']];
}

// add
if (isset($_POST['add'])) {
	if (FormController::add()) {
	} else if (function_exists('_expand_add')) {
		$error = _expand_add($queryConfig['table'], $inputs);
	} else {
		form_db_add($queryConfig['table'], $inputs);
	}

	header('Location: ' . $pageConfig['return-url']);
}

// edit
if (isset($_POST['edit'])) {
	if (FormController::edit(['id' => $id])) {
	} else if (function_exists('_expand_edit')) {
		$error = _expand_edit($queryConfig['table'], $inputs, $queryConfig['index-field'], $id);
	} else {
		form_db_update($queryConfig['table'], $inputs, $queryConfig['index-field'], $id);
	}

	header('Location: ' . $pageConfig['return-url']);
}

<?php

//load template parts


//get id
$id = $_GET['id'] ?? false;
$page['edit'] = $id ? true : false;

//style
$view->styles->add(SERVER_ROOT . '/app/templates/styles/admin.css');
$this->scripts->add(SERVER_ROOT . '/app/templates/scripts/admin.js');

//load logic
$this->logic->add('admin', 'template');
require_once(SERVER_ROOT . '/pages/logic/' . $this->name . '.php');

//setup variables
$error = $error ?? false;
$done = $done ?? false;
$inputs = $inputs ?? false;
$page['free'] = $free ?? false;
$page['upload'] = $upload ?? false;
$page['confirmation'] = $confirmation ?? false;

$url_query = explode('&', $_SERVER['REQUEST_URI'], 2)[1] ?? '';
if (empty($url_query) && strpos($_SERVER['REQUEST_URI'], '?')) $url_query = explode('?', $_SERVER['REQUEST_URI'], 2)[1];
$page['back_url'] = $back_url ?? 'list?' . str_replace('filter=', 'id=', $url_query);

//fetch data
if ($page['edit']) $entry = $entry ?? DBOld::row('* FROM ' . $tbl . ' WHERE ' . $index . '=?', [$id]);
else $entry = false;

//setup title
if (isset($title)) $page['title'] = $title;
else if ($page['edit']) $page['title'] = 'Modifica: ' . $entry[$index_show];
else $page['title'] = $this->title;

//add
if (isset($_POST['add'])) {

	if (function_exists('_expand_add')) $error = _expand_add($tbl, $inputs);
	else form_db_add($tbl, $inputs);

	if (!$error) header('Location: ' . $page['back_url']);
}

//edit
if (isset($_POST['edit'])) {

	if (function_exists('_expand_edit')) $error = _expand_edit($tbl, $inputs, $index, $id);
	else form_db_update($tbl, $inputs, $index, $id);

	if (!$error) header('Location: ' . $page['back_url']);
}

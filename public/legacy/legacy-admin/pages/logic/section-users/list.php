<?php

$pageConfig['add'] = false;
$queryConfig['table'] = 'users LEFT JOIN stores ON users.ID = stores.userID';
$queryConfig['index'] = 'ID';
$queryConfig['select'] = ['ID', 'username', 'email', 'users.time', 'stores.name as sellerName'];
$queryConfig['where-search'] = "ID = ? OR LOWER(username) LIKE CONCAT('%',?,'%') OR email LIKE CONCAT('%',?,'%') OR LOWER(stores.name) LIKE CONCAT('%',?,'%')";

// fields
$fields = [
	['title' => 'ID', 'name' => 'ID'],
	['title' => 'Username', 'name' => 'username', 'size' => 3],
	['title' => 'E-mail', 'name' => 'email', 'size' => 3],
	['title' => 'Shop', 'name' => 'sellerName', 'size' => 3],
	['title' => 'Data', 'name' => 'time'],

	['type' => 'btn'],
];

$buttons = [
	['type' => 'submit', 'title' => 'Login', 'name' => 'login', 'color' => 'gre'],
	['type' => 'edit'],
	['type' => 'delete'],
];

// login as a user
if (isset($_POST['login'])) {
	$id = $_POST['ID'];

	$_SESSION['userId'] = $id;

	header('Location: ' . PUBLIC_CLIENT_ROOT . '/profile');
}

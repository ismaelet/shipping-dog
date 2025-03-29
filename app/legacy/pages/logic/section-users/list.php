<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

$pageConfig['add'] = false;
$queryConfig['table'] = 'users LEFT JOIN stores ON users.id = stores.user_id';
$queryConfig['index'] = 'id';
$queryConfig['select'] = ['id', 'username', 'email', 'users.time', 'stores.name as sellerName'];
$queryConfig['where-search'] = "ID = ? OR LOWER(username) LIKE CONCAT('%',?,'%') OR email LIKE CONCAT('%',?,'%') OR LOWER(stores.name) LIKE CONCAT('%',?,'%')";

// fields
$fields = [
	['title' => 'id', 'name' => 'id'],
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
	$id = $_POST['id'];
	$user = User::find($id);

	Auth::logout();
	Auth::login($user);

	return redirect('login');
}

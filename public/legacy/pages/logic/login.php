<?php

/* TEMP */
$resTemp = DB::select('* FROM users WHERE email="" AND mail<>""');

while ($rowTemp = $resTemp->fetch_assoc()) {
	DB::update('users SET email=? WHERE ID = ?', [$rowTemp['mail'], $rowTemp['ID']]);
}

/* TEMP */
$resTemp = DB::select('* FROM addresses WHERE email="" AND mail<>""');

while ($rowTemp = $resTemp->fetch_assoc()) {
	DB::update('addresses SET email=? WHERE ID = ?', [$rowTemp['mail'], $rowTemp['ID']]);
}
/* END TEMP */

// setup variabiles
$error = false;

// login
if (isset($_POST['submit'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$remember = isset($_POST['remember']);
	$url = $_POST['url'] ?? false;

	$error = standard_login($username, $password, $url, $remember);
}

<?php

/* TEMP */

use App\Models\User;

$resTemp = DBOld::select('* FROM users WHERE email="" AND mail<>""');

while ($rowTemp = $resTemp->fetch_assoc()) {
	DBOld::update('users SET email=? WHERE ID = ?', [$rowTemp['mail'], $rowTemp['ID']]);
}

/* TEMP */
$resTemp = DBOld::select('* FROM addresses WHERE email="" AND mail<>""');

while ($rowTemp = $resTemp->fetch_assoc()) {
	DBOld::update('addresses SET email=? WHERE ID = ?', [$rowTemp['mail'], $rowTemp['ID']]);
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

	$user = User::where('email', $username)->first();

	if ($user && password_verify($password, $user->password)) {
		auth()->login($user, $remember);

		redirect_old('/');
	} else {
		return back()->with('error', 'Dati di accesso errati');
	}
}

<?php

StyleLoader::add('login', 'page');

// setup page
$error = false;

// submit
if (isset($_POST['submit'])) {
	// check for errors
	if (DB::exists('users WHERE username = ?', [$_POST['username']])) $error = 'user_match';
	else if (DB::exists('users WHERE email=?', [$_POST['email']])) $error = 'email_match';
	else if (strlen($_POST['password']) < 6) $error = 'pass_short';
	else if ($_POST['password'] != $_POST['pass_conf']) $error = 'pass_match';
	else {
		// add user
		$id = DB::add('users (username, password, email) VALUES (?, MD5(?), ?)', [$_POST['username'], $_POST['password'], $_POST['email']]);

		DB::add('links (userID) VALUES (?)', [$id]);

		standard_login($_POST['username'], $_POST['password']);
	}
}

$inputs = [
	[
		['type' => 'lbl', 'text' => 'Username'],
		['name' => 'username', 'text' => 'Username', 'maxlength' => 20, 'required' => true,],
		['type' => 'error', 'error' => 'user_match', 'text' => "L'username scelto è già associato ad un altro account",],
	],
	[
		['type' => 'lbl', 'text' => 'Password',],
		['type' => 'password', 'text' => 'Password', 'name' => 'password', 'maxlength' => 20, 'required' => true,],
		['type' => 'error', 'error' => 'pass_short', 'text' => "La password scelta è troppo corta",],
		['type' => 'password', 'text' => 'Conferma la password', 'name' => 'pass_conf', 'maxlength' => 20, 'required' => true,],
		['type' => 'error', 'error' => 'pass_match', 'text' => 'La password scelta non corrisponde alla conferma',],
	],
	[
		['type' => 'lbl', 'text' => 'E-email'],
		['type' => 'email', 'text' => 'Indirizzo e-email', 'name' => 'email', 'maxlength' => 50, 'required' => true,],
		['type' => 'error', 'error' => 'email_match', 'text' => 'Indirizzo email già associato ad un altro account',],
	],
	[
		['type' => 'cbx', 'text' => 'Ho letto e acconsento al <a href="' . CLIENT_ROOT . '/infos/privacy">trattamento dei dati</a>', 'required' => true,],
	],
];

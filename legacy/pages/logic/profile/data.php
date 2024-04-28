<?php

$loc = 'data';
$error = $_SESSION['error'] ?? false;
$done = $_SESSION['done'] ?? false;

unset($_SESSION['error']);
unset($_SESSION['done']);

// updat data
if (isset($_POST['edit_data'])) {

	$username = $_POST['username'];
	$email = trim($_POST['email']);
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);

	if (\Legacy\System\Classes\LegacyDB::exists('users WHERE username = ? AND ID<>?', [$username, auth()->id()])) $_SESSION['error'] = 'user_match';
	else if (\Legacy\System\Classes\LegacyDB::exists('users WHERE email=? AND ID<>?', [$email, auth()->id()])) $_SESSION['error'] = 'email_match';
	else {
		\Legacy\System\Classes\LegacyDB::update('users SET username = ?, email=?, first_name = ?, last_name = ? WHERE id = ?', [$username, $email, $first_name, $last_name, auth()->id()]);

		$_SESSION['done'] = 'data';
	}

	redirect_old('#data-anch');
}

// update password
if (isset($_POST['edit_pass'])) {

	$passOld = $_POST['passOld'];
	$passNew = $_POST['passNew'];
	$passConf = $_POST['passConf'];

	$db_password = \Legacy\System\Classes\LegacyDB::find('password FROM users WHERE id = ?', auth()->id());

	// update old MD5 passwords
	if ($db_password[0] != '$' && md5($passOld) == $db_password) {

		$db_password = password_hash($passOld, PASSWORD_DEFAULT);
		\Legacy\System\Classes\LegacyDB::update('users SET password=? WHERE id = ?', [$db_password, auth()->id()]);
	}

	if (!password_verify($passOld, $db_password)) $_SESSION['error'] = 'pass_bad';
	else if ($passNew != $passConf) $_SESSION['error'] = 'pass_match';
	else {

		$passNew = password_hash($passNew, PASSWORD_DEFAULT);

		\Legacy\System\Classes\LegacyDB::update('users SET password=? WHERE id = ?', [$passNew, auth()->id()]);

		$_SESSION['done'] = 'pass';
	}

	redirect_old('#pass-anch');
}

// change icon
if (isset($_POST['edit_icon'])) {

	$file = '/uploads/users/icons/' . auth()->id() . '.jpg';

	upload_img_OLD($file, 'img', 150, 'crop');
}

// delete account
if (isset($_POST['delete-account'])) {
	delete_user(auth()->id());
	logout();
}

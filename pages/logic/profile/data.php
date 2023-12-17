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
	$fname = trim($_POST['fname']);
	$lname = trim($_POST['lname']);

	if (DB::exists('users WHERE username=? AND ID<>?', [$username, userId()])) $_SESSION['error'] = 'user_match';
	else if (DB::exists('users WHERE email=? AND ID<>?', [$email, userId()])) $_SESSION['error'] = 'email_match';
	else {
		DB::update('users SET username=?, email=?, firstname=?, lastname=? WHERE ID=?', [$username, $email, $fname, $lname, userId()]);

		$_SESSION['done'] = 'data';
	}

	redirect('#data-anch');
}

// update password
if (isset($_POST['edit_pass'])) {

	$passOld = $_POST['passOld'];
	$passNew = $_POST['passNew'];
	$passConf = $_POST['passConf'];

	$db_password = DB::find('password FROM users WHERE ID=?', userId());

	// update old MD5 passwords
	if ($db_password[0] != '$' && md5($passOld) == $db_password) {

		$db_password = password_hash($passOld, PASSWORD_DEFAULT);
		DB::update('users SET password=? WHERE ID=?', [$db_password, userId()]);
	}

	if (!password_verify($passOld, $db_password)) $_SESSION['error'] = 'pass_bad';
	else if ($passNew != $passConf) $_SESSION['error'] = 'pass_match';
	else {

		$passNew = password_hash($passNew, PASSWORD_DEFAULT);

		DB::update('users SET password=? WHERE ID=?', [$passNew, userId()]);

		$_SESSION['done'] = 'pass';
	}

	redirect('#pass-anch');
}

// change icon
if (isset($_POST['edit_icon'])) {

	$file = '/uploads/img_profile/' . userId() . '.jpg';

	upload_img_OLD($file, 'img', 150, 'crop');
}

// delete account
if (isset($_POST['delete-account'])) {
	delete_user(userId());
	logout();
}

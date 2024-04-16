<?php



$error = false;

// setup functions
if (isset($_POST['guest'])) {
	\App\Helpers\App::cartOld()->advanceAndredirect_old();
}

// login
if (isset($_POST['submit'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	$error = standard_login($username, $password, '/cart', true);
}

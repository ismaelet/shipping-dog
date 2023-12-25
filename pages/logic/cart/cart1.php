<?php

use function Ecommerce\cart;

$error = false;

// setup functions
if (isset($_POST['guest'])) {
	cart()->advanceAndRedirect();
}

// login
if (isset($_POST['submit'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	$error = standard_login($username, $password, '/cart', true);
}

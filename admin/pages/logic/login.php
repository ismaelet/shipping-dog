<?php

// setup variabiles
$error = false;

// login
if (isset($_POST['submit'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$error = standard_login($username, $password);
}

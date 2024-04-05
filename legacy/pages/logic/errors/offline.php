<?php

if (isset($_POST['submit'])) {

	if (isset($_SESSION['userID'])) logout('login');
	else redirect_old('login');
}

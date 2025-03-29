<?php

if (isset($_POST['submit'])) {

	if (isset($_SESSION['user_id'])) logout('login');
	else redirect_old('login');
}

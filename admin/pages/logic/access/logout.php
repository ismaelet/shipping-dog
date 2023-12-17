<?php

//setup page
$header['floating'] = true;

//logout
if (isset($_POST['submit'])) {
	logout();
}

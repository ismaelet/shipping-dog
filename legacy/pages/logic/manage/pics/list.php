<?php

global $user;

$res_sel = DBOld::select('* FROM home_pics WHERE desID IN (SELECT ID FROM designs WHERE userID = ?) ORDER BY ID DESC', [auth()->id()]);
$selN = $res_sel->num_rows;

// delete entry
if (isset($_POST['delete'])) {
	$imgID = $_POST['imgID'];

	DBOld::delete('home_pics WHERE ID = ?', [$imgID]);

	unlink(SERVER_ROOT . '/uploads/users/testimonials/' . $imgID . '.jpg');

	redirect_old();
}

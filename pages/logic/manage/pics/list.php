<?php

global $user;

$res_sel = DB::select('* FROM home_pics WHERE desID IN (SELECT ID FROM designs WHERE userID = ?) ORDER BY ID DESC', [$user->id]);
$selN = $res_sel->num_rows;

// delete entry
if (isset($_POST['delete'])) {
	$imgID = $_POST['imgID'];

	DB::delete('home_pics WHERE ID = ?', [$imgID]);

	unlink(SERVER_ROOT . '/uploads/img_fans/' . $imgID . '.jpg');

	redirect();
}

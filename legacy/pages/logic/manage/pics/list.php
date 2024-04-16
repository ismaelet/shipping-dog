<?php

global $user;

$res_sel = DBOld::select('* FROM home_pics WHERE design_id IN (SELECT id FROM designs WHERE user_id = ?) ORDER BY id DESC', [auth()->id()]);
$selN = $res_sel->num_rows;

// delete entry
if (isset($_POST['delete'])) {
	$imgID = $_POST['imgID'];

	DBOld::delete('home_pics WHERE id = ?', [$imgID]);

	unlink(SERVER_ROOT . '/uploads/users/testimonials/' . $imgID . '.jpg');

	redirect_old();
}

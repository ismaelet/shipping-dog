<?php

$full = false;

require_once(substr(__DIR__, 0, strpos(__DIR__, 'app')) . '/system/setup/setup.php');

$id = $_POST['id'];
$followed = $_POST['followed'];

DBOld::delete('feed WHERE type=4 AND userID = ? AND srcID = ?', [$id, auth()->id()]);

if ($followed) DBOld::delete('followers WHERE userID = ? AND followID = ?', [auth()->id(), $id]);

else if (!DBOld::exists('followers WHERE userID = ? AND followID = ?', [auth()->id(), $id])) {

	$follID = DBOld::add('followers (userID, followID) VALUES (?, ?)', [auth()->id(), $id]);

	DBOld::add('feed (feedID, userID, srcID, type) VALUES (?, ?, ?, ?)', [$follID, $id, auth()->id(), 4]);
}

<?php

$full = false;

require_once(substr(__DIR__, 0, strpos(__DIR__, 'app')) . '/system/setup/setup.php');

$id = $_POST['id'];
$followed = $_POST['followed'];

DBOld::delete('feed WHERE type=4 AND user_id = ? AND srcID = ?', [$id, auth()->id()]);

if ($followed) DBOld::delete('followers WHERE user_id = ? AND follower_id = ?', [auth()->id(), $id]);

else if (!DBOld::exists('followers WHERE user_id = ? AND follower_id = ?', [auth()->id(), $id])) {

	$follID = DBOld::add('followers (user_id, follower_id) VALUES (?, ?)', [auth()->id(), $id]);

	DBOld::add('feed (feed_id, user_id, srcID, type) VALUES (?, ?, ?, ?)', [$follID, $id, auth()->id(), 4]);
}

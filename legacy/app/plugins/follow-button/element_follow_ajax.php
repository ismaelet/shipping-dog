<?php

$full = false;

require_once(substr(__DIR__, 0, strpos(__DIR__, 'app')) . '/system/setup/setup.php');

$id = $_POST['id'];
$followed = $_POST['followed'];

\Legacy\System\Classes\LegacyDB::delete('feed WHERE type=4 AND user_id = ? AND srcID = ?', [$id, auth()->id()]);

if ($followed) \Legacy\System\Classes\LegacyDB::delete('followers WHERE user_id = ? AND follower_id = ?', [auth()->id(), $id]);

else if (!\Legacy\System\Classes\LegacyDB::exists('followers WHERE user_id = ? AND follower_id = ?', [auth()->id(), $id])) {

	$follID = \Legacy\System\Classes\LegacyDB::add('followers (user_id, follower_id) VALUES (?, ?)', [auth()->id(), $id]);

	\Legacy\System\Classes\LegacyDB::add('feed (feed_id, user_id, srcID, type) VALUES (?, ?, ?, ?)', [$follID, $id, auth()->id(), 4]);
}

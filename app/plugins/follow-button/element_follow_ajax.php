<?php

$full = false;

require_once(substr(__DIR__, 0, strpos(__DIR__, 'app')).'/system/setup/setup.php');

$id = $_POST['id'];
$followed = $_POST['followed'];

DB::delete('feed WHERE type=4 AND userID=? AND srcID=?', [$id, $user->id]);

if ($followed) DB::delete('followers WHERE userID=? AND followID=?', [$user->id, $id]);

else if (!DB::exists('followers WHERE userID=? AND followID=?', [$user->id, $id])) {

	$follID = DB::add('followers (userID, followID) VALUES (?, ?)', [$user->id, $id]);

	DB::add('feed (feedID, userID, srcID, type) VALUES (?, ?, ?, ?)', [$follID, $id, $user->id, 4]);
}
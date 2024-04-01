<?php

use User\FeedEntry;

$loc = 'feed';

// get feed
$feedEntries = [];
$feedEntryRows = DB::select(
	'
	orders.ID as eventId, 2 AS type, carts.time AS time
		FROM orders INNER JOIN carts ON orders.cartID = carts.ID WHERE sellerId = ? AND carts.status > 0
	UNION ALL
	SELECT ID as eventId, 1 AS type, time
		FROM news
	ORDER BY time DESC
	LIMIT 15
	',
	[userId()]
);

while ($feedEntryRow = $feedEntryRows->fetch_assoc()) {
	$feedEntries[] = new FeedEntry($feedEntryRow);
}

$feedEntryCount = count($feedEntries);

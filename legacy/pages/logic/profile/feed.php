<?php

use User\FeedEntry;

$loc = 'feed';

// get feed
$feedEntries = [];
$feedEntryRows = DBOld::select(
	'
	orders.id as event_id, 2 AS type, carts.time AS time
		FROM orders INNER JOIN carts ON orders.cart_id = carts.id WHERE seller_id = ? AND carts.status > 0
	UNION ALL
	SELECT id as event_id, 1 AS type, time
		FROM news
	ORDER BY time DESC
	LIMIT 15
	',
	[auth()->id()]
);

while ($feedEntryRow = $feedEntryRows->fetch_assoc()) {
	$feedEntries[] = new FeedEntry($feedEntryRow);
}

$feedEntryCount = count($feedEntries);

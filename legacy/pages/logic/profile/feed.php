<?php

use Legacy\Classes\User\FeedEntry;

$loc = 'feed';

// get feed
$feedEntries = [];
$feedEntryRows = \Legacy\System\Classes\LegacyDB::select(
	'
	order_items.id as event_id, 2 AS type, orders.created_at
		FROM order_items INNER JOIN orders ON order_items.order_id = orders.id WHERE seller_id = ? AND orders.status > 0
	UNION ALL
	SELECT id as event_id, 1 AS type, created_at
		FROM news
	ORDER BY created_at DESC
	LIMIT 15
	',
	[auth()->id()]
);

while ($feedEntryRow = $feedEntryRows->fetch_assoc()) {
	$feedEntries[] = new FeedEntry($feedEntryRow);
}

$feedEntryCount = count($feedEntries);

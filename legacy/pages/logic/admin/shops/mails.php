<?php

//fetch emails list
$shops = \Legacy\System\Classes\LegacyDB::select("email FROM users WHERE id>1 AND admin=0 AND id in (SELECT user_id from stores WHERE status=1) AND email NOT IN (

	'info@shippingdog.com', 'andrea_trentini94@ya.it', 'giorgiamiranda@yahoo.it', 'temp@gmail.com', 'valeriosiciliano@email.it', 'inseriscilatuaemail@gmail.it', 'ciriii@hotemail.kt', 'ssss@gmail.com', 'noncancellareisma@hotemail.it', 'aaa@aaa.com', '  quellidelcalciomercato@aol.com', 'yourmail@gmail.com'

	) ORDER BY id ASC
");

$block = 49;

$blocksN = ceil($shops->num_rows / $block);

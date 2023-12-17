<?php

//fetch emails list
$shops = DB::select("email FROM users WHERE id>1 AND admin=0 AND id in (SELECT userID from stores WHERE status=1) AND email NOT IN (

	'info@shippingdog.com', 'andrea_trentini94@ya.it', 'giorgiamiranda@yahoo.it', 'temp@gmail.com', 'valeriosiciliano@email.it', 'inseriscilatuaemail@gmail.it', 'ciriii@hotemail.kt', 'ssss@gmail.com', 'noncancellareisma@hotemail.it', 'aaa@aaa.com', '  quellidelcalciomercato@aol.com', 'yourmail@gmail.com'

	) ORDER BY ID ASC
");

$block = 49;

$blocksN = ceil($shops->num_rows / $block);

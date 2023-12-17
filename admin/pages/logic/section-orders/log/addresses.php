<?php

// theme
StyleLoader::add('section-orders/log/log', 'page');

// fetch date
$date = DB::find('value FROM variables WHERE name=?', ['log_date']);
$res_sel = DB::select('* FROM carts WHERE status>0 AND time>TIMESTAMP(?) ORDER BY time ASC', [$date]);

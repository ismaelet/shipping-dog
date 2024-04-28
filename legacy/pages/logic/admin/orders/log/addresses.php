<?php

// theme
\Legacy\System\Classes\StyleLoader::add('section-orders/log/log', 'page');

// fetch date
$date = \Legacy\System\Classes\LegacyDB::find('value FROM variables WHERE name = ?', ['log_date']);
$res_sel = \Legacy\System\Classes\LegacyDB::select('* FROM carts WHERE status>0 AND time>TIMESTAMP(?) ORDER BY time ASC', [$date]);

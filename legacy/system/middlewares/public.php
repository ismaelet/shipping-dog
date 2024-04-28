<?php

$public = \Legacy\System\Classes\LegacyDB::find('value FROM variables WHERE name = ?', ['public']);

if (!$public && !is_admin()) redirect_old('/offline');

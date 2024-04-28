<?php

global $user;

$loc = 'shop';

$res_sel = \Legacy\System\Classes\LegacyDB::select('* FROM likes WHERE user_id = ? ORDER BY time DESC', [auth()->id()]);
$selN = $res_sel->num_rows;

<?php

global $user;

$loc = 'shop';

$res_sel = DB::select('* FROM likes WHERE userID = ? ORDER BY time DESC', [$user->id]);
$selN = $res_sel->num_rows;

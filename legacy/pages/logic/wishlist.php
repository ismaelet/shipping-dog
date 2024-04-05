<?php

global $user;

$loc = 'shop';

$res_sel = DBOld::select('* FROM likes WHERE userID = ? ORDER BY time DESC', [auth()->id()]);
$selN = $res_sel->num_rows;

<?php

global $user;

$loc = 'orders';

$res_sel = DBOld::select('ID FROM carts WHERE status<>0 and buyer=? ORDER BY time DESC', [auth()->id()]);

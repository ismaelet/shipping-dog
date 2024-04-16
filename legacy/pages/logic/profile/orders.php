<?php

global $user;

$loc = 'orders';

$res_sel = DBOld::select('id FROM carts WHERE status<>0 and user_id=? ORDER BY time DESC', [auth()->id()]);

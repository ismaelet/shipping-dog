<?php

require_once('cart.php');

//setup variables
$loc = 4;
$error = false;

//setup cart
$cart = cart_setup_old($user->user_idId, $loc);

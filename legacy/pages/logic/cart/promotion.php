<?php

use App\Helpers\EcommerceHelper;

$order = cart();
$token = request()->token;

if ($order) {
	if ($order->token == $token && $order->payment_method_id == 4 && in_array($order->status, [EcommerceHelper::statusFromTag('received'), EcommerceHelper::statusFromTag('open')])) {
		$order->confirm();
	}

	redirect_old($order->recapUrlWithToken());
} else {
	redirect_old(route('cart'));
}

<?php

// order completed
RouteOld::add('/order', function () {
	ViewOld::setPage('order/completed');
	StyleLoader::add('cart', 'page');
	ViewOld::show();
});

// order status
RouteOld::add(['/order/status', '/infos/status'], function () {
	ViewOld::setPage('order/status');
	ViewOld::$title = 'Stato Ordine';
	ViewOld::$template = 'form';
	ViewOld::show();
});

// change order
RouteOld::add(['/change', '/refund'], function () {
	$stepId = $_GET['step'] ?? 1;

	ViewOld::setPage('order/refund/refund' . $stepId);
	ViewOld::$title = 'Resi e cambi';
	ViewOld::$template = 'form';
	StyleLoader::add('order/refund', 'page', 3);
	ScriptLoader::add('order/refund', 'page', 3);
	ViewOld::show();
});

RouteOld::add('/refund/paypal', function () {
	ViewOld::setPage('order/refund/paypal');
	ViewOld::$title = 'Resi e Cambi';
	ViewOld::$template = 'void';
	ViewOld::show();
});

// change address
RouteOld::add('/order/address', function () {
	ViewOld::setPage('order/address');
	ViewOld::$title = 'Cambio indirizzo';
	ViewOld::$template = 'form';
	ViewOld::show();
});

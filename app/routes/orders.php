<?php

// order completed
Route::add('/order', function () {
	View::setPage('order/completed');
	StyleLoader::add('cart', 'page');
	View::show();
});

// order status
Route::add(['/order/status', '/infos/status'], function () {
	View::setPage('order/status');
	View::$title = 'Stato Ordine';
	View::$template = 'form';
	View::show();
});

// change order
Route::add(['/change', '/refund'], function () {
	$stepId = $_GET['step'] ?? 1;

	View::setPage('order/refund/refund' . $stepId);
	View::$title = 'Resi e cambi';
	View::$template = 'form';
	StyleLoader::add('order/refund', 'page', 3);
	ScriptLoader::add('order/refund', 'page', 3);
	View::show();
});

Route::add('/refund/paypal', function () {
	View::setPage('order/refund/paypal');
	View::$title = 'Resi e Cambi';
	View::$template = 'void';
	View::show();
});

// change address
Route::add('/order/address', function () {
	View::setPage('order/address');
	View::$title = 'Cambio indirizzo';
	View::$template = 'form';
	View::show();
});

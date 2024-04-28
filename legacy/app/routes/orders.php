<?php

// order completed
\Legacy\System\Classes\LegacyRoute::add('/order', function () {
	\Legacy\System\Classes\LegacyView::setPage('order/completed');
	\Legacy\System\Classes\StyleLoader::add('cart', 'page');
	\Legacy\System\Classes\LegacyView::show();
});

// order status
\Legacy\System\Classes\LegacyRoute::add(['/order/status', '/infos/status'], function () {
	\Legacy\System\Classes\LegacyView::setPage('order/status');
	\Legacy\System\Classes\LegacyView::$title = 'Stato Ordine';
	\Legacy\System\Classes\LegacyView::$template = 'form';
	\Legacy\System\Classes\LegacyView::show();
});

// change order
\Legacy\System\Classes\LegacyRoute::add(['/change', '/refund'], function () {
	$stepId = $_GET['step'] ?? 1;

	\Legacy\System\Classes\LegacyView::setPage('order/refund/refund' . $stepId);
	\Legacy\System\Classes\LegacyView::$title = 'Resi e cambi';
	\Legacy\System\Classes\LegacyView::$template = 'form';
	\Legacy\System\Classes\StyleLoader::add('order/refund', 'page', 3);
	\Legacy\System\Classes\ScriptLoader::add('order/refund', 'page', 3);
	\Legacy\System\Classes\LegacyView::show();
});

\Legacy\System\Classes\LegacyRoute::add('/refund/paypal', function () {
	\Legacy\System\Classes\LegacyView::setPage('order/refund/paypal');
	\Legacy\System\Classes\LegacyView::$title = 'Resi e Cambi';
	\Legacy\System\Classes\LegacyView::$template = 'void';
	\Legacy\System\Classes\LegacyView::show();
});

// change address
\Legacy\System\Classes\LegacyRoute::add('/order/address', function () {
	\Legacy\System\Classes\LegacyView::setPage('order/address');
	\Legacy\System\Classes\LegacyView::$title = 'Cambio indirizzo';
	\Legacy\System\Classes\LegacyView::$template = 'form';
	\Legacy\System\Classes\LegacyView::show();
});

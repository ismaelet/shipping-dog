<?php

use function Ecommerce\cart;
use function Ecommerce\ecommerce_config;

Route::add('/cart', function () {
	$cartSteps = ecommerce_config('cart-page')['steps'];
	$currentStepId = $_GET['step-id'] ?? null;
	$currentStepId = in_array($currentStepId, array_keys($cartSteps)) ? $currentStepId : null;

	if (!cart() || !cart()->quantity) {
		redirect('/cart/empty');
	}

	if (!$currentStepId || $currentStepId > cart()->stepId) {
		redirect(cart()->openUrl);
	}

	if ($cartSteps[$currentStepId][1] == 'login' && logged()) {
		cart()->advanceAndRedirect(2);
	}

	Logic::add('cart', 'page');
	StyleLoader::add('cart', 'page');
	ScriptLoader::add('cart', 'page');
	View::setPage('cart/cart' . $currentStepId)
		->setTitle('Carrello')
		->set('currentStepId', $currentStepId)
		->show();
});

// pay with paypal
Route::add('/pay/paypal', function () {
	View::setPage('cart/paypal')
		->setTitle('Pagamento')
		->setTemplate('void')
		->show();
});

// pay with stripe
Route::add('/pay/stripe', function () {
	View::setPage('cart/stripe')
		->setTitle('Carrello')
		->show();
});

// free promotion
Route::add('/pay/promotion', function () {
	View::setPage('cart/promotion')
		->setTitle('Carrello')
		->setTemplate('void')
		->show();
});

// payment success page
Route::add('/cart/success', function () {
	View::setPage('cart/success')
		->setTitle('Successo')
		->setTemplate('void')
		->show();
});

// empty cart
Route::add('/cart/empty', function () {
	if (cart() && cart()->quantity) {
		redirect('/cart');
	}

	View::setPage('cart/empty')
		->setTitle('Carrello Vuoto')
		->show();
});

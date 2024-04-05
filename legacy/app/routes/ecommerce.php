<?php


use function Ecommerce\ecommerce_config;

RouteOld::add('/cart', function () {
	$cartSteps = ecommerce_config('cart-page')['steps'];
	$currentStepId = $_GET['step-id'] ?? null;
	$currentStepId = in_array($currentStepId, array_keys($cartSteps)) ? $currentStepId : null;

	if (!cart_old() || !cart_old()->quantity) {
		redirect_old('/cart/empty');
	}

	if (!$currentStepId || $currentStepId > cart_old()->stepId) {
		redirect_old(cart_old()->openUrl);
	}

	if ($cartSteps[$currentStepId][1] == 'login' && auth()->check()) {
		cart_old()->advanceAndredirect_old(2);
	}

	Logic::add('cart', 'page');
	StyleLoader::add('cart', 'page');
	ScriptLoader::add('cart', 'page');
	ViewOld::setPage('cart/cart' . $currentStepId)
		->setTitle('Carrello')
		->set('currentStepId', $currentStepId)
		->show();
});

// pay with paypal
RouteOld::add('/pay/paypal', function () {
	ViewOld::setPage('cart/paypal')
		->setTitle('Pagamento')
		->setTemplate('void')
		->show();
});

// pay with stripe
RouteOld::add('/pay/stripe', function () {
	ViewOld::setPage('cart/stripe')
		->setTitle('Carrello')
		->show();
});

// free promotion
RouteOld::add('/pay/promotion', function () {
	ViewOld::setPage('cart/promotion')
		->setTitle('Carrello')
		->setTemplate('void')
		->show();
});

// payment success page
RouteOld::add('/cart/success', function () {
	ViewOld::setPage('cart/success')
		->setTitle('Successo')
		->setTemplate('void')
		->show();
});

// empty cart
RouteOld::add('/cart/empty', function () {
	if (cart_old() && cart_old()->quantity) {
		redirect_old('/cart');
	}

	ViewOld::setPage('cart/empty')
		->setTitle('Carrello Vuoto')
		->show();
});

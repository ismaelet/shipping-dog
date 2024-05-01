<?php

use App\Helpers\ArrayHelper;

\Legacy\System\Classes\LegacyRoute::add('/cart/*', function (string $path) {
	$cartSteps = config('ecommerce.cart.steps');
	$currentStep = ArrayHelper::explodeLast('/', $path)[1];
	$currentStepId = array_search($currentStep, array_column($cartSteps, 'tag')) + 1;
	$cartStep = cart()?->step;

	if (!$cartStep) {
		redirect_old('/cart/empty');
	}

	if (!$currentStepId || $currentStepId > $cartStep) {
		redirect_old('/cart/' . $cartSteps[$cartStep]['tag']);
	}

	if ($currentStep == 'access' && auth()->check()) {
		cart_old()->advanceAndredirect_old(2);
	}

	\Legacy\System\Classes\Logic::add('cart', 'page');
	\Legacy\System\Classes\StyleLoader::add('cart', 'page');
	\Legacy\System\Classes\ScriptLoader::add('cart', 'page');
	\Legacy\System\Classes\LegacyView::setPage('cart/cart' . $currentStepId)
		->setTitle('Carrello')
		->set('currentStepId', $currentStepId)
		->show();
});

// pay with paypal
\Legacy\System\Classes\LegacyRoute::add('/pay/paypal', function () {
	\Legacy\System\Classes\LegacyView::setPage('cart/paypal')
		->setTitle('Pagamento')
		->setTemplate('void')
		->show();
});

// pay with stripe
\Legacy\System\Classes\LegacyRoute::add('/pay/stripe', function () {
	\Legacy\System\Classes\LegacyView::setPage('cart/stripe')
		->setTitle('Carrello')
		->show();
});

// free promotion
\Legacy\System\Classes\LegacyRoute::add('/pay/promotion', function () {
	\Legacy\System\Classes\LegacyView::setPage('cart/promotion')
		->setTitle('Carrello')
		->setTemplate('void')
		->show();
});

// payment success page
\Legacy\System\Classes\LegacyRoute::add('/cart/success', function () {
	\Legacy\System\Classes\LegacyView::setPage('cart/success')
		->setTitle('Successo')
		->setTemplate('void')
		->show();
});

// empty cart
\Legacy\System\Classes\LegacyRoute::add('/cart/empty', function () {
	if (cart_old() && cart_old()->quantity) {
		redirect_old('/cart');
	}

	\Legacy\System\Classes\LegacyView::setPage('cart/empty')
		->setTitle('Carrello Vuoto')
		->show();
});

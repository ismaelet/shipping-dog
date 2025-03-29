<?php

use App\Helpers\ArrayHelper;
use App\Helpers\EcommerceHelper;

\Legacy\System\Classes\LegacyRoute::add('/cart/*', function (string $path) {
	$orderItemsteps = config('ecommerce.cart.steps');
	$currentStep = ArrayHelper::explodeLast('/', $path)[1];
	$currentStepId = EcommerceHelper::stepFromTag($currentStep);

	$orderItemstep = cart()?->step;

	if (!$orderItemstep) {
		redirect_old('/cart/empty');
	}

	if (!$currentStepId || $currentStepId > $orderItemstep) {
		redirect_old('/cart/' . $orderItemsteps[$orderItemstep]['tag']);
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

// free promotion
\Legacy\System\Classes\LegacyRoute::add('/pay/promotion', function () {
	\Legacy\System\Classes\LegacyView::setPage('cart/promotion')
		->setTitle('Carrello')
		->setTemplate('void')
		->show();
});
